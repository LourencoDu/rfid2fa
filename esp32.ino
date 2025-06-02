#include <SPI.h>
#include <MFRC522.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define RST_PIN   32
#define SS_PIN    33
#define SCK_PIN   14
#define MOSI_PIN  27
#define MISO_PIN  25

const char* ssid = "REDE";
const char* password = "SENHA";

// Troque pelo IP do seu computador na rede
const char* serverUrl = "http://192.168.18.13/rfid2fa/api/leitura/cadastro";

MFRC522 rfid(SS_PIN, RST_PIN);

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

  SPI.begin(SCK_PIN, MISO_PIN, MOSI_PIN, SS_PIN);
  rfid.PCD_Init();
  Serial.println("Aproxime o cartão...");
}

void loop() {
  if (!rfid.PICC_IsNewCardPresent() || !rfid.PICC_ReadCardSerial()) return;

  String uid_str = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) uid_str += "0";
    uid_str += String(rfid.uid.uidByte[i], HEX);
  }
  uid_str.toUpperCase();

  Serial.print("UID detectado: ");
  Serial.println(uid_str);

  // Enviar requisição HTTP POST
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");

    String jsonBody = "{\"uid_cartao\":\"" + uid_str + "\"}";
    int httpResponseCode = http.POST(jsonBody);

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

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
  delay(2000); // Evita leituras duplicadas
}
