#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
ESP8266WebServer server(80);
#include <ArduinoJson.h>
#include "FS.h"
//#include <WiFiClientSecureBearSSL.h>

//const char fingerprint[] PROGMEM= "0E 8F 01 01 FE 4F 31 0E 37 53 0B 83 04 79 F3 7F D4 BA 05 AA";

String ssid_c = "";
String pass_c = "";
String API = "http://identifikasi.inodroid-narotama.com/push_data.php";
int configAP = 0;

int LoopTime_Soll = 9;
int LoopTime_Angepasst = LoopTime_Soll;
int LoopTime_Bisher = LoopTime_Soll;
unsigned long LoopTime_Start = 0;

void setup()
{
  Serial.begin(115200);
  Serial.println();

  if (!SPIFFS.begin()) {
    Serial.println("Failed to mount file system");
    return;
  }

  if (!loadFS()) {
    if (!saveConfig("BELUM DISETING", "BELUM DISETING")) {
      ssid_c = "BELUM DISETING";
      pass_c = "BELUM DISETING";
      Serial.println("Failed to save config");
    } else {
      Serial.println("Config saved");
    }
  } else {
    Serial.println("Config loaded");
  }
  Serial.print("Configuring access point...");
  WiFi.softAP("sanner i-parking");
  IPAddress myIP = WiFi.softAPIP();
  Serial.print("AP IP address: ");
  Serial.println(myIP);
  server.on("/", handleRoot);
  server.on("/stop_ap", StopAP);
  server.on("/config", configScanner);
  server.on("/do_config", actionConfig);
  server.on("/reset", resetStorage);
  server.begin();
  Serial.println("HTTP server started");
  while(configAP == 0){
    server.handleClient();
  }

  WiFi.begin(ssid_c, pass_c);
  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println();

  Serial.print("Connected, IP address: ");
  Serial.println(WiFi.localIP());
  Serial.setDebugOutput(true);
  delay(2000);
}

void loop() {
  int n = WiFi.scanNetworks();
  Serial.println("scan done");
  if (n == 0)
    Serial.println("no networks found");
  else
  {
    Serial.print(n);
    Serial.println(" networks found");
    for (int i = 0; i < n; ++i)
    {
      Serial.print(i + 1);
      Serial.print(": ");
      Serial.print(WiFi.SSID(i));
      Serial.print(" (");
      Serial.print(WiFi.RSSI(i));
      Serial.print(")");
      Serial.println((WiFi.encryptionType(i) == ENC_TYPE_NONE)?" ":"*");
      findPattern(WiFi.SSID(i));
      delay(500);
    }
  }
  Serial.println();
   
  LoopTime_Bisher = millis() - LoopTime_Start;   
  if(LoopTime_Bisher < LoopTime_Soll){
    delay(LoopTime_Soll - LoopTime_Bisher);
  }
  LoopTime_Angepasst = millis() - LoopTime_Start;
  LoopTime_Start = millis();
}

void findPattern(String ssid){
  if(ssid.indexOf("KEY_SECRET") >= 0){
    sendReq(ssid);
  }else{
    Serial.println("Bukan SSID Dives");
  }
}

void sendReq(String key){
  if (WiFi.status() == WL_CONNECTED) { 
//    std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);  
//    client->setFingerprint(fingerprint);
    HTTPClient http;
    http.begin(API);  
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    int httpCode = http.POST("key="+key);                                                                  //Send the request
    if (httpCode > 0) { 
      String payload = http.getString();   
      Serial.println(payload);             
    }else{
      Serial.println("[HTTP] GET... failed, error");
    }
    http.end();
  }else{
    Serial.println("Not connected");
  }
}

void handleRoot() {
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Setup Scanner</title></head><body><center><h1><strong>Selamat datang di set up scanner <u>DIVES</u></strong></h1></center><br/><table border='1' cellpadding='8'><tr><td>Default SSID</td><td>"+ssid_c+"</td></tr><tr><td>Default Password</td><td>"+pass_c+"</td></tr></table><br/><center><a href='/config'>Config</a> || <a href='/stop_ap'>Run Scanner</a> || <a href='/reset'>Reset Config</a><center></body></html>");
}

void configScanner(){
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Config</title></head><body><form action='/do_config' method='post'><label>Config SSID</label><br/><input type='text' name='ssid'><br/><label>Config Password SSID</label><br/><input type='text' name='pass'><br/><br/><input type='submit' value='Save'></form></body></html>");
}

void actionConfig(){
  String message = "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Process Config</title></head><body>";
  if (server.method() == HTTP_POST){
    ssid_c = server.arg(0);
    pass_c = server.arg(1);
    if (!saveConfig(ssid_c, pass_c)) {
      Serial.println("Failed to save config");
    } else {
      Serial.println("Config saved");
    }
    message += "<center><h1 style='background-color: lightgreen'><strong>Configuration Success</strong></h1><br/><a href='/'>Back</a></center>";
  }else{
    message += "<center><h1><strong>Inputan anda tidak kami ketahui</strong></h1><br/><a href='/config'>Back</a></center>";
  }
  message += "</body></html>";
  server.send(200, "text/html", message);
}

void StopAP(){
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Process Config</title></head><body><center><h1><strong>Device anda sudah berganti mode sebagai client dengan SSID: <u>"+ssid_c+"</u>, PASS: <u>"+pass_c+"</u></strong></h1></center></body></html>");
  delay(5000);
  configAP = 1;
}

void resetStorage(){
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Process Config</title></head><body><center><h1><strong style='background-color: lightred'>Config reseted</strong></h1><br/><a href='/'>Back</a></center></body></html>");
  delay(5000);
  SPIFFS.remove("/config.json");
  delay(3000);
  setup();
}

bool loadFS(){
  File configFile = SPIFFS.open("/config.json", "r");
  if (!configFile) {
    Serial.println("Failed to open config file");
    return false;
  }
  size_t size = configFile.size();
  std::unique_ptr<char[]> buf(new char[size]);
  configFile.readBytes(buf.get(), size);
  StaticJsonDocument<200> doc;
  auto error = deserializeJson(doc, buf.get());
  if (error) {
    Serial.println("Failed to parse config file");
    return false;
  }
  const char* ssid = doc["ssid"];
  const char* pass = doc["pass"];
  ssid_c = string2char(ssid);
  pass_c = string2char(pass);
}

bool saveConfig(String ssid, String pass) {
  StaticJsonDocument<200> doc;
  doc["ssid"] = ssid;
  doc["pass"] = pass;
  ssid_c = ssid;
  pass_c = pass;

  File configFile = SPIFFS.open("/config.json", "w");
  if (!configFile) {
    Serial.println("Failed to open config file for writing");
    return false;
  }

  serializeJson(doc, configFile);
  return true;
}

char* string2char(String command){
    if(command.length()!=0){
        char *p = const_cast<char*>(command.c_str());
        return p;
    }
}
