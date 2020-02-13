# This source for scanner and client vehicle

[![N|Solid](https://cldup.com/dTxpPi9lDf.thumb.png)](#)

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)]()

terdapat 2 source yang akan di install atau _embed_ pada module anda, source ini masih dalam development yang hanya bisa di terapkan pada module ***ESP8266 All Series.***

## Kebutuhan
- Arduino IDE
- Library
    - [ESP8266](https://github.com/esp8266/Arduino)
    - [ArduinoJson](https://github.com/bblanchon/ArduinoJson)
- Setting Arduino IDE
    - Board => `Wemos D1 R1`
    - Flash Size => `4M (2M SPIFFS)`
    - Upload Speed => `115200`
    - Erase Flash => `All Flash Contents`

## Run
Buka folder `FORSCANNER` dan buka file tersebut dengan _Arduino IDE_ lalu sesuaikan dengan ***COM*** yang anda pakai.
- Sesuaikan ***URL API*** anda pada baris ***10*** dengan cara
    - buka ***CLI*** anda, lalu ketikan ipconfig / ifconfig, maka anda akan menemukan ip device anda `ex: 192.168.1.13`
    - folder project web dives yang sebelumnya dijalankan, `ex: web_dives`
    - maka ubah baris ***10*** tersebut dengan, `ex: 192.168.1.13/web_dives/push_data.php`
- (optional) jika anda ingin mengubah `SSID Configuration` pada baris ***40***

Setelah configurasi selesai, selanjutnya jalankan dengan klik button `Compile and Upload`, Proses berlangsung kurang lebih 5 Menit. Jika sudah buka settings WiFi `Smartphone` anda, maka anda akan menemukan SSID yang telah terbuat. Sambungkan dan setelah terhubung, buka browser anda dan ketikan pada kolom url `192.168.4.1`, silahkan konfigurasi WiFi Client anda.

Jika sudah selesai tahap tersebut, untuk menguji nya. aktifkan `Hotspot` Smartphone anda dengan name `KEY_SECREt1`, dan lihat hasilnya pada `Serial Monitor anda`

**Enjoy!**

## Next
- [ ] Membuat Dinamis Configurasi Scanner (Mode STA + AP)
- [ ] Membuat Dinamis Configurasi Client (Mode AP + Hidden Pattern)