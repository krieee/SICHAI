#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>
#include <ArduinoJson.h>

#define DHTPIN D4
#define DHTTYPE DHT22
#define RELAY_PIN D1

const char* ssid = "DEVICE2_AP";
const char* password = "device2pass";
const char* gatewayIP = "192.168.4.1";

DHT dht(DHTPIN, DHTTYPE);

unsigned long lastSensorRead = 0;
const unsigned long sensorInterval = 200; // 200ms

unsigned long lastSendTime = 0;
const unsigned long sendInterval = 3000; // 3s

// Cache sensor data for sending
float cachedTemp = 0.0, cachedHum = 0.0;
int cachedSoil = 0;
bool relayState = false;

void setup() {
  Serial.begin(115200);
  dht.begin();
  pinMode(RELAY_PIN, OUTPUT);
  digitalWrite(RELAY_PIN, LOW);

  WiFi.begin(ssid, password);
  Serial.print("Connecting to Device 2");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to Device 2");
}

void loop() {
  unsigned long now = millis();

  // Fast sensor reading and relay logic
  if (now - lastSensorRead >= sensorInterval) {
    lastSensorRead = now;

    float t = dht.readTemperature();
    float h = dht.readHumidity();
    int soil = analogRead(A0);

    if (!isnan(t) && !isnan(h)) {
      cachedTemp = t;
      cachedHum = h;
      cachedSoil = soil;

      // Relay logic — update instantly
      if (soil < 600) {
        digitalWrite(RELAY_PIN, HIGH);
        relayState = true;
      } else {
        digitalWrite(RELAY_PIN, LOW);
        relayState = false;
      }
    } else {
      Serial.println("Failed to read DHT sensor!");
    }
  }

  // Separate timer for data sending
  if (now - lastSendTime >= sendInterval && WiFi.status() == WL_CONNECTED) {
    lastSendTime = now;

    StaticJsonDocument<200> doc;
    doc["temperature"] = cachedTemp;
    doc["humidity"] = cachedHum;
    doc["soil"] = cachedSoil;
    doc["relay"] = relayState;

    String json;
    serializeJson(doc, json);

    WiFiClient client;
    HTTPClient http;
    http.begin(client, "http://" + String(gatewayIP) + "/data");
    http.addHeader("Content-Type", "application/json");

    int httpCode = http.POST(json);
    http.end();

    Serial.println("Sent: " + json + " | HTTP Response: " + String(httpCode));
  }
}