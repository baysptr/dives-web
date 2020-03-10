#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

ESP8266WebServer server(80);
#include <ArduinoJson.h>
#include "FS.h"

String ssid_c = "";
String pass_c = "";
int configAP = 0;

void setup() {
  Serial.begin(115200);
  Serial.println();

  if (!SPIFFS.begin()) {
    Serial.println("Failed to mount file system");
    return;
  }

  if (!loadFS()) {
    if (!saveConfig("CLIENT DIVES", "CLIENT DIVES")) {
      ssid_c = "CLIENT DIVES";
      pass_c = "CLIENT DIVES";
      Serial.println("Failed to save config");
    } else {
      Serial.println("Config saved");
    }
  } else {
    Serial.println("Config loaded");
  }

  Serial.print("Configuring access point...");
  WiFi.softAP(ssid_c);
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
  ESP.reset();
}

void loop() {
  // put your main code here, to run repeatedly:

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

void handleRoot() {
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Setup Scanner</title></head><body><center><h1><strong>Selamat datang di set up client <u>DIVES</u></strong></h1></center><br/><table border='1' cellpadding='8'><tr><td>Key Secret</td><td>"+ssid_c+"</td></tr><tr><td>Encypt</td><td>"+pass_c+"</td></tr></table><br/><center><a href='/config'>Config</a> || <a href='/stop_ap'>Run Scanner</a> || <a href='/reset'>Reset Config</a><center></body></html>");
}

void configScanner(){
  server.send(200, "text/html", "<html><head><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Config</title></head><body><form action='/do_config' method='post'><label>Key Secret</label><br/><input type='text' name='ssid'><br/><label>Encypt</label><br/><input type='text' name='pass' disabled value='bea48bc68f83b5a14fff30086f7c4001'><br/><br/><input type='submit' value='Save'></form></body></html>");
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
