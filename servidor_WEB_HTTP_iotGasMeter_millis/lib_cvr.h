
#include <ESP8266WiFi.h>
#include <wm_strings_en.h>
#include <WiFiManager.h>
#include <DNSServer.h>
#include <ESP8266WebServer.h>

#include <Ticker.h>

#define pinLedWiFi D4   

//variables para una red de area local metodo 1
//String url = "http://192.168.0.7/iotgasmeter/prueba_recibe.php"; //para el metodo1
//String protocol = "http://";
//String host = "192.168.0.7";
//WiFiClient wifiClient; //para el metodo 1

void parpadeoLedWiFi();
void MyWiFiConnect();
void printMessageWiFi();

//Instancia a la clase Ticker para el parpadeo del led indicador de conexion wifi
Ticker ticker_wifi;

void parpadeoLedWiFi (){
  //cambiar de estado el LED
  byte estado = digitalRead (pinLedWiFi);
  digitalWrite (pinLedWiFi, !estado);
}

void printMessageWiFi(){
  Serial.println("********************************************");
  Serial.print("Conectado a la red WiFi: ");
  Serial.println(WiFi.SSID());
  Serial.print("IP: ");
  Serial.println(WiFi.localIP());
  Serial.print("macAdress: ");
  Serial.println(WiFi.macAddress());
  Serial.println("********************************************");
}

void MyWiFiConnect(){
  Serial.begin(115200);
  
  // Modo del pin 
  pinMode (pinLedWiFi, OUTPUT);
  // Empezamos el temporizador que hara parpadear el LED
  ticker_wifi.attach (0.2, parpadeoLedWiFi);
  
  // Creamos una instancia de la clase WiFiManager
  WiFiManager wifiManager;

  // Descomentar para resetear configuracion
  //wifiManager.resetSettings();

  // creamos AP y Portal cautivo y comprobamos si se establece la conexion
  if(!wifiManager.autoConnect("ESP8266Temp", "e1234567890")){
    Serial.println ("Fallo en la conexion (timeout)");
    ESP.reset();
    delay(1000);
  }

  Serial.println("Ya estás conectado");
  Serial.println ("ya estas conectado");
  
  // Eliminamos el temporizador
  ticker_wifi.detach();
  
  // Apagamos el led 
  digitalWrite (pinLedWiFi, HIGH);
  printMessageWiFi();
}

/*void method1(){
  HTTPClient http; // Declaramos un objeto de la clase HTTPClient
  http.begin(wifiClient, url); // Especifica el destino de la peticion
  http.addHeader("Content-Type", "application/x-www-form-urlencoded"); //Especifica el encabezado del contenido

  conteo=random(100,200);
  v_leido=conteo*0.01;
  String postData = "idDispositivo=" + idDispositivo + "&v_leido=" + String(v_leido);

  int httpCode = http.POST(postData); // Envia la peticion
  String respuesta = http.getString(); //obtiene la respuesta del servidor

  Serial.println(httpCode); // Imprime el codigo de respuesta del servidor
  Serial.println(respuesta); //Imprime la respuesta que proporciona el servidor

  //{SERVO:0, LED:0}<br>{DISPOSITIVO:medidor2, VLEIDO:45, CONSUMO:25}&
  int ini = respuesta.indexOf(":"); //busca la posicion del caracter ":"
  int fin = respuesta.indexOf(",", ini+1); //busca la posicion del caracter ","
  servo = respuesta.substring(ini+1, fin).toInt();

  ini = respuesta.indexOf(":", fin); //busca la posicion del caracter ":" desde el fin de la anterio busqueda
  fin = respuesta.indexOf("}",ini); //busca la posicion del caracter ","
  led = respuesta.substring(ini+1, fin).toInt();

  Serial.println(servo);
  Serial.println(led);



  http.end(); // Se cierra la conexion

}*/
