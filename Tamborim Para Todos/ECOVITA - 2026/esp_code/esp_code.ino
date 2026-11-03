#include <WiFi.h>
#include <WebServer.h>

// ======================================================
// CONFIGURAÇÃO DA REDE
// ======================================================

const char* ssid = "NIT_2G";
const char* password = "nit2026@";

// IP FIXO DO ESP32
IPAddress local_IP(192, 168, 1, 250);

// Gateway do roteador
IPAddress gateway(192, 168, 1, 1);

// Máscara
IPAddress subnet(255, 255, 255, 0);

// DNS
IPAddress primaryDNS(8, 8, 8, 8);
IPAddress secondaryDNS(8, 8, 4, 4);


// ======================================================
// LED INTERNO
// ======================================================

// Na maioria dos ESP32 DevKit o LED interno está no GPIO 2
#define LED_PIN 2

bool estadoLed = false;


// ======================================================
// SERVIDOR WEB
// ======================================================

WebServer server(80);


// ======================================================
// FUNÇÃO PARA LIBERAR CORS
// ======================================================

void adicionarCORS() {

  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.sendHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
  server.sendHeader("Access-Control-Allow-Headers", "Content-Type");
}


// ======================================================
// ROTA PRINCIPAL
// ======================================================

void paginaInicial() {

  adicionarCORS();

  String mensagem = "";

  mensagem += "ESP32 ONLINE\n";
  mensagem += "IP: ";
  mensagem += WiFi.localIP().toString();
  mensagem += "\n";

  mensagem += "LED: ";

  if (estadoLed) {
    mensagem += "LIGADO";
  } else {
    mensagem += "DESLIGADO";
  }

  server.send(200, "text/plain", mensagem);
}


// ======================================================
// LIGAR LED
// ======================================================

void ligarLed() {

  digitalWrite(LED_PIN, HIGH);

  estadoLed = true;

  Serial.println("LED LIGADO");

  adicionarCORS();

  server.send(
    200,
    "application/json",
    "{\"status\":\"ok\",\"led\":\"ligado\"}"
  );
}


// ======================================================
// DESLIGAR LED
// ======================================================

void desligarLed() {

  digitalWrite(LED_PIN, LOW);

  estadoLed = false;

  Serial.println("LED DESLIGADO");

  adicionarCORS();

  server.send(
    200,
    "application/json",
    "{\"status\":\"ok\",\"led\":\"desligado\"}"
  );
}


// ======================================================
// CONSULTAR ESTADO DO LED
// ======================================================

void consultarLed() {

  adicionarCORS();

  if (estadoLed) {

    server.send(
      200,
      "application/json",
      "{\"led\":\"ligado\"}"
    );

  } else {

    server.send(
      200,
      "application/json",
      "{\"led\":\"desligado\"}"
    );
  }
}


// ======================================================
// OPTIONS / CORS
// ======================================================

void responderOptions() {

  adicionarCORS();

  server.send(204);
}


// ======================================================
// SETUP
// ======================================================

void setup() {

  Serial.begin(115200);

  delay(1000);

  Serial.println();
  Serial.println("==============================");
  Serial.println("      INICIANDO ESP32");
  Serial.println("==============================");


  // ----------------------------------------------------
  // LED
  // ----------------------------------------------------

  pinMode(LED_PIN, OUTPUT);

  digitalWrite(LED_PIN, LOW);

  estadoLed = false;


  // ----------------------------------------------------
  // WIFI
  // ----------------------------------------------------

  WiFi.mode(WIFI_STA);

  WiFi.setAutoReconnect(true);
  WiFi.persistent(true);


  // ----------------------------------------------------
  // IP FIXO
  // ----------------------------------------------------

  Serial.println();
  Serial.println("Configurando IP fixo...");

  if (!WiFi.config(
        local_IP,
        gateway,
        subnet,
        primaryDNS,
        secondaryDNS
      )) {

    Serial.println("ERRO ao configurar IP fixo!");

  } else {

    Serial.println("IP fixo configurado.");
  }


  // ----------------------------------------------------
  // CONECTAR
  // ----------------------------------------------------

  Serial.print("Conectando em: ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);


  int tentativas = 0;

  while (WiFi.status() != WL_CONNECTED && tentativas < 40) {

    delay(500);

    Serial.print(".");

    tentativas++;
  }


  Serial.println();


  // ----------------------------------------------------
  // VERIFICAR CONEXÃO
  // ----------------------------------------------------

  if (WiFi.status() == WL_CONNECTED) {

    Serial.println();
    Serial.println("==============================");
    Serial.println("       WIFI CONECTADO");
    Serial.println("==============================");

    Serial.print("SSID: ");
    Serial.println(WiFi.SSID());

    Serial.print("IP: ");
    Serial.println(WiFi.localIP());

    Serial.print("Gateway: ");
    Serial.println(WiFi.gatewayIP());

    Serial.print("Mascara: ");
    Serial.println(WiFi.subnetMask());

    Serial.print("Sinal WiFi: ");
    Serial.print(WiFi.RSSI());
    Serial.println(" dBm");

    Serial.println();
    Serial.println("TESTES:");
    Serial.println("http://192.168.1.250/");
    Serial.println("http://192.168.1.250/led/on");
    Serial.println("http://192.168.1.250/led/off");
    Serial.println("http://192.168.1.250/led/status");

  } else {

    Serial.println();
    Serial.println("ERRO: Nao foi possivel conectar ao WiFi.");
  }


  // ====================================================
  // ROTAS
  // ====================================================

  server.on("/", HTTP_GET, paginaInicial);

  server.on("/led/on", HTTP_GET, ligarLed);

  server.on("/led/off", HTTP_GET, desligarLed);

  server.on("/led/status", HTTP_GET, consultarLed);


  // OPTIONS para CORS

  server.on("/led/on", HTTP_OPTIONS, responderOptions);

  server.on("/led/off", HTTP_OPTIONS, responderOptions);

  server.on("/led/status", HTTP_OPTIONS, responderOptions);


  // ====================================================
  // INICIAR SERVIDOR
  // ====================================================

  server.begin();

  Serial.println();
  Serial.println("Servidor HTTP iniciado na porta 80.");
}


// ======================================================
// LOOP
// ======================================================

void loop() {

  // Atender requisições
  server.handleClient();


  // Se perder WiFi tenta reconectar
  if (WiFi.status() != WL_CONNECTED) {

    Serial.println("WiFi desconectado. Tentando reconectar...");

    WiFi.disconnect();

    WiFi.begin(ssid, password);

    delay(3000);
  }
}