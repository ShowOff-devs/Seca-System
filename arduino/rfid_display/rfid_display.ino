#include <WiFiNINA.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <hd44780.h>
#include <hd44780ioClass/hd44780_I2Cexp.h>

// WiFi credentials
const char* ssid = "::";
const char* password = "password";

// MQTT Broker settings
const char* mqtt_server = "192.168.5.7";
const int mqtt_port = 1883;
const char* mqtt_user = "";
const char* mqtt_password = "";
const char* mqtt_topic = "rfid/scans";

// Initialize I2C LCD
hd44780_I2Cexp lcd;

WiFiClient wifiClient;
PubSubClient client(wifiClient);

void setup() {
  Serial.begin(9600);
  
  // Initialize I2C
  Wire.begin();
  
  // Initialize LCD
  int status = lcd.begin(16, 2);
  if(status) {
    Serial.print("LCD initialization failed: ");
    Serial.println(status);
    while(1);
  }
  
  lcd.backlight();
  lcd.print("RFID Scanner");
  lcd.setCursor(0, 1);
  lcd.print("Waiting...");
  
  // Connect to WiFi
  setupWiFi();
  
  // Setup MQTT
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
}

void setupWiFi() {
  delay(10);
  Serial.println("Connecting to WiFi...");
  
  while (WiFi.begin(ssid, password) != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nConnected to WiFi");
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    if (client.connect("ArduinoClient", mqtt_user, mqtt_password)) {
      Serial.println("connected");
      client.subscribe(mqtt_topic);
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" retrying in 5 seconds");
      delay(5000);
    }
  }
}

void callback(char* topic, byte* payload, unsigned int length) {
  // Convert payload to string
  char message[length + 1];
  memcpy(message, payload, length);
  message[length] = '\0';
  
  // Parse JSON (simple parsing for demonstration)
  char rfid_no[32] = "";
  char device_id[32] = "";
  
  // Extract RFID number
  char* rfid_start = strstr(message, "\"rfid_no\":\"");
  if (rfid_start) {
    rfid_start += 11;
    char* rfid_end = strchr(rfid_start, '\"');
    if (rfid_end) {
      int len = rfid_end - rfid_start;
      strncpy(rfid_no, rfid_start, len);
      rfid_no[len] = '\0';
    }
  }
  
  // Display on LCD
  lcd.clear();
  lcd.print("RFID: ");
  lcd.print(rfid_no);
  lcd.setCursor(0, 1);
  lcd.print("Scan received!");
  
  // Also print to Serial
  Serial.println("Received RFID scan: " + String(rfid_no));
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
} 