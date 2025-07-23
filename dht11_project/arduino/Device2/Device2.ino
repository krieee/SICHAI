#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>

const char* ap_ssid = "DEVICE2_AP";
const char* ap_password = "device2pass";

const char* router_ssid = "Apex ING";       // Your router SSID
const char* router_password = "WelcomeApexING"; // Your router password

ESP8266WebServer server(80);

void setup() {
  Serial.begin(115200);

  // Enable both Access Point and Station mode
  WiFi.mode(WIFI_AP_STA);

  // Start Access Point for Device 1 to connect
  WiFi.softAP(ap_ssid, ap_password);
  Serial.println("SoftAP IP: " + WiFi.softAPIP().toString());

  // Connect to your home router for internet
  WiFi.begin(router_ssid, router_password);
  Serial.print("Connecting to internet");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nConnected to internet with IP: " + WiFi.localIP().toString());

  // Setup HTTP POST endpoint for receiving data from Device 1
  server.on("/data", HTTP_POST, []() {
    String json = server.arg("plain");
    Serial.println("Received from Device 1: " + json);

    // Forward this data to your external PHP server
    if (WiFi.status() == WL_CONNECTED) {
      WiFiClient client;
      HTTPClient http;
      http.begin(client, "http://electroniclabnepal.com/apex/upload.php");  // <-- Use your actual server address
      http.addHeader("Content-Type", "application/json");

      int httpCode = http.POST(json);
      String response = http.getString();
      http.end();

      Serial.println("Forwarded to PHP Server. HTTP Code: " + String(httpCode));
      Serial.println("Server Response: " + response);
    } else {
      Serial.println("Not connected to internet. Cannot forward.");
    }

    server.send(200, "text/plain", "Received");
  });

  server.begin();
}

void loop() {
  server.handleClient();
}
