#include <Wire.h>
#include <Adafruit_PN532.h>
#include <WiFi.h>
#include <HTTPClient.h>

// Pinos I²C padrão no ESP32-C3
#define SDA_PIN 8
#define SCL_PIN 9

// Inicializa o PN532 usando I²C
Adafruit_PN532 nfc(SDA_PIN, SCL_PIN);

// Wi-Fi
const char* ssid = "eduardo_notebook";
const char* password = "35663593";

// API de destino
const char* serverUrl = "http://192.168.18.13/rfid2fa/api/leitura/cadastro";

unsigned long tempoInicio, tempoLeitura, tempoHttp;

void setup() {
  Serial.begin(115200);
  delay(1000);

  // Conecta ao Wi-Fi
  Serial.println("Conectando ao Wi-Fi...");
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\n✅ Conectado ao Wi-Fi!");
  Serial.println(WiFi.localIP());

  // Inicializa NFC
  nfc.begin();
  uint32_t versiondata = nfc.getFirmwareVersion();
  if (!versiondata) {
    Serial.println("❌ Módulo NFC não detectado.");
    while (1); // trava
  }

  Serial.println("✅ Módulo NFC detectado!");
  nfc.SAMConfig(); // Prepara para leitura
  Serial.println("Aproxime o cartão...");
}

void loop() {
  uint8_t uid[7];
  uint8_t uidLength;

  if (nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength)) {
  tempoInicio = micros();   
    // UID
    String uid_str = "";
    for (uint8_t i = 0; i < uidLength; i++) {
      if (uid[i] < 0x10) uid_str += "0";
      uid_str += String(uid[i], HEX);
    }
    uid_str.toUpperCase();

    tempoLeitura = micros();
    float tempoNfcLeituraMs = (tempoLeitura - tempoInicio) / 1000.0;
    Serial.print("⏱️ Tempo leitura NFC + Montagem do UID (ms): ");
    Serial.println(tempoNfcLeituraMs);

    tempoLeitura = millis();
    // Enviar para servidor
    if (WiFi.status() == WL_CONNECTED) {
      HTTPClient http;
      http.begin(serverUrl);
      http.addHeader("Content-Type", "application/json");

      String jsonBody = "{\"uid_cartao\":\"" + uid_str + "\"}";
      int httpResponseCode = http.POST(jsonBody);

      tempoHttp = millis();
      Serial.print("⏱️ Tempo HTTP (ms): ");
      Serial.println(tempoHttp - tempoLeitura);

      if (httpResponseCode > 0) {
        Serial.print("✅ Código HTTP: ");
        Serial.println(httpResponseCode);
        String payload = http.getString();
        Serial.println("Resposta: " + payload);
      } else {
        Serial.print("❌ Erro na requisição: ");
        Serial.println(httpResponseCode);
      }
      http.end();
    } else {
      Serial.println("❌ Wi-Fi desconectado!");
    }

    delay(2000); // Evita leituras seguidas
  }
}
