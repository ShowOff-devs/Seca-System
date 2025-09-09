#include <WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <Adafruit_PN532.h>

// WiFi credentials
const char* ssid = "::";
const char* password = "password";

// MQTT Broker settings
const char* mqtt_server = "192.168.29.7";
const int mqtt_port = 1883;
const char* mqtt_user = "";
const char* mqtt_password = "";
const char* mqtt_topic = "rfid/scans";

// Device ID
const char* device_id = "ESP32_RFID_1";

// PN532 I2C pins
#define PN532_IRQ   (2)
#define PN532_RESET (3)  // Not connected by default

// Initialize PN532 with I2C
Adafruit_PN532 nfc(PN532_IRQ);

WiFiClient espClient;
PubSubClient client(espClient);

void setup() {
  Serial.begin(115200);
  
  // Initialize I2C
  Wire.begin();
  
  // Initialize NFC
  nfc.begin();
  uint32_t versiondata = nfc.getFirmwareVersion();
  if (!versiondata) {
    Serial.println("No PN532 NFC module found!");
    while (1);
  }
  
  // Configure PN532
  nfc.SAMConfig();
  
  // Connect to WiFi
  setupWiFi();
  
  // Setup MQTT
  client.setServer(mqtt_server, mqtt_port);
}

void setupWiFi() {
  delay(10);
  Serial.println("Connecting to WiFi...");
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nConnected to WiFi");
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    if (client.connect("ESP32Client", mqtt_user, mqtt_password)) {
      Serial.println("connected");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" retrying in 5 seconds");
      delay(5000);
    }
  }
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  
  // Check for NFC card
  uint8_t uid[] = { 0, 0, 0, 0, 0, 0, 0 };
  uint8_t uidLength;
  
  if (nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength)) {
    // Convert UID to string
    char uidString[32];
    for (uint8_t i = 0; i < uidLength; i++) {
      sprintf(uidString + (i * 2), "%02X", uid[i]);
    }
    
    // Create JSON payload
    char payload[100];
    snprintf(payload, sizeof(payload), 
             "{\"rfid_no\":\"%s\",\"device_id\":\"%s\",\"scan_type\":\"check_in\",\"scanned_at\":\"%s\"}",
             uidString, device_id, getCurrentTime().c_str());
    
    // Publish to MQTT
    client.publish(mqtt_topic, payload);
    Serial.println("RFID tag detected and published: " + String(uidString));
    
    // Wait a bit before next scan
    delay(2000);
  }
}

String getCurrentTime() {
  // You might want to implement NTP to get accurate time
  // For now, we'll just return a placeholder
  return "2024-03-21T12:00:00Z";
} 