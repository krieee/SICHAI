#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <Wire.h>
#include <U8g2lib.h>

// WiFi credentials
const char* router_ssid = "Apex ING";
const char* router_password = "WelcomeApexING";

// Webhost JSON URL
const char* json_url = "https://electroniclabnepal.com/apex/latest_entries.json";

// OLED Display object
U8G2_SH1106_128X64_NONAME_F_HW_I2C u8g2(U8G2_R0, U8X8_PIN_NONE);

// Variables to store fetched data
String temperature = "--";
String humidity = "--";
String moisture = "--";
String timestamp = "--";

void setup() {
  Serial.begin(115200);

  // Initialize OLED
  u8g2.begin();
  u8g2.clearBuffer();
  u8g2.setFont(u8g2_font_ncenB08_tr);
  u8g2.drawStr(0, 10, "Connecting WiFi...");
  u8g2.sendBuffer();

  // Connect to WiFi
  WiFi.begin(router_ssid, router_password);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected with IP: " + WiFi.localIP().toString());

  u8g2.clearBuffer();
  u8g2.drawStr(0, 10, "WiFi Connected!");
  u8g2.sendBuffer();
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClientSecure client;
    client.setInsecure();  // Ignore SSL certificate

    http.begin(client, json_url);
    int httpCode = http.GET();

    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println("Received JSON: " + payload);

      StaticJsonDocument<1024> doc;
      DeserializationError error = deserializeJson(doc, payload);

      if (!error) {
        float tempVal = doc[0]["temp"];
        float humVal = doc[0]["hum"];
        int soilVal = doc[0]["soil"];
        const char* timeVal = doc[0]["timestamp"];

        temperature = String(tempVal, 1);
        humidity = String(humVal, 1);
        moisture = String(soilVal);
        timestamp = String(timeVal);

        Serial.println("Temp: " + temperature + " °C");
        Serial.println("Humidity: " + humidity + " %");
        Serial.println("Soil: " + moisture);
        Serial.println("Time: " + timestamp);
      } else {
        Serial.println("JSON Parsing Failed");
      }
    } else {
      Serial.println("HTTP GET Failed, code: " + String(httpCode));
    }

    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }

  // Display on OLED
  u8g2.clearBuffer();
  u8g2.setFont(u8g2_font_ncenB08_tr);

  u8g2.drawStr(0, 10, "HELLO SICHAI!");                          // Line 1
  u8g2.drawStr(0, 25, ("Temp: " + temperature + " C").c_str()); // Line 2
  u8g2.drawStr(0, 40, ("Humidity: " + humidity + " %").c_str()); // Line 3
  u8g2.drawStr(0, 55, ("Soil: " + moisture).c_str());           // Line 4

  // Display timestamp at bottom (line 5)
  u8g2.setCursor(0, 64);
  if (timestamp.length() >= 16) {
    u8g2.print(timestamp.substring(5, 16));  // MM-DD HH:MM
  } else {
    u8g2.print(timestamp);
  }

  u8g2.sendBuffer();

  delay(2000);  // Refresh every 5 seconds
}
