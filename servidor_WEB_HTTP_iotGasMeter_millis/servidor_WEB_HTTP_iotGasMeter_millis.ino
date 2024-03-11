#include <Servo.h>
#include "lib_cvr.h"
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#define  buttonPin 5
#define LED D0
//variables para la funcion millis
unsigned long tiempo;
unsigned long tiempo2=0;
//variables para el servidor web
String protocol ="https://";
String host ="iotgasmeter.000webhostapp.com";
String resourse ="/iotgasmeter/prueba_recibe.php";
int port = 80;
String url = protocol + host+ resourse;
String idDispositivo = "medidor1";
float v_leido = 0;                  // volumen registrado en m3
int servo=0;
int led=0;
int consumo = 37;                   // contador para el numero de pulsos
int buttonState = 0;                // Estado actual del pulsador
int lastButtonState = 0;            // estado previo del pulsador

Servo valvula;

void setup() {
  // put your setup code here, to run once:
  pinMode(buttonPin, INPUT);
  pinMode(LED, OUTPUT);
  Serial.begin(115200);
  digitalWrite (LED, !LOW);
  MyWiFiConnect();

  valvula.attach(4);
  valvula.write(0);
}

void method2(){
  tiempo=millis();
  WiFiClient client;
  if(!client.connect(host, port)){ //intenta conectarse al servidor web
    Serial.println("Fallo al conectar");
    client.stop();
    return;
  } 
  if (tiempo-tiempo2>=3000){
    tiempo2=tiempo;
    Serial.print("Tiempo transcurrido: ");
    Serial.println(tiempo);
    v_leido=consumo*0.01;
    String postData = "idDispositivo=" + idDispositivo + "&v_leido=" + String(v_leido);
    
    client.println("POST https://iotgasmeter.000webhostapp.com/iotgasmeter/prueba_recibe.php HTTP/1.0");
    client.println("HOST: iotgasmeter.000webhostapp.com");
    //client.println("Cache-Control: no-cache");
    client.println("Accept: /*" );
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(postData.length());
    client.println();
    client.println(postData);
  
    Serial.println("\nDatos enviados: {v_leido:" + String(v_leido) + "}\n");
    //Serial.println("***** INICIA RESPUESTA *****");
    
    
    int code = -1;
    // cadena recibida a la peticion hecha: {SERVO:0, LED:0}<br>{DISPOSITIVO:medidor2, VLEIDO:45, CONSUMO:25}&
    while (client.connected()){
      if (client.available()){
        String line = client.readStringUntil('&');    // se obtiene en una cadena la respuesta recibida a la peticion hecha
        Serial.println(line);
        int ini = line.indexOf(" ");
        int fin = line.indexOf(" ",ini+1);
        //Serial.println("code = " + line.substring(ini+1, fin)); // imprime en el monitor serial el codigo de respuesta a la peticion hecha
        int ini2 = line.indexOf("{");
        int fin2 = line.indexOf("}",ini2+1);
        //Serial.println("respuesta = " + line.substring(ini2, fin2+1));  // imprime la cadena entre los caracteres especificados de la respuesta a la peticion hecha
        code = line.substring(ini+1, fin).toInt();
        String respuesta = respuesta;
        if (code==200){
          respuesta = line.substring(ini2, fin2+1);
          Serial.println("codigo= " + String(code));
          Serial.println("respuesta= " + respuesta);

          int ini = respuesta.indexOf(":"); //busca la posicion del caracter ":"
          int fin = respuesta.indexOf(",", ini+1); //busca la posicion del caracter ","
          servo = respuesta.substring(ini+1, fin).toInt();

          ini = respuesta.indexOf(":", fin); //busca la posicion del caracter ":" desde la posicion fin anterior
          fin = respuesta.indexOf("}",ini); //busca la posicion del caracter "," desde la posicion ini anterior
          led = respuesta.substring(ini+1, fin).toInt();
          
        }else{
          int ini = respuesta.indexOf(":"); //busca la posicion del caracter ":"
          int fin = respuesta.indexOf(",", ini+1); //busca la posicion del caracter ","
          servo = respuesta.substring(ini+1, fin).toInt();
    
          ini = respuesta.indexOf(":", fin); //busca la posicion del caracter ":"
          fin = respuesta.indexOf("}",ini); //busca la posicion del caracter ","
          led = respuesta.substring(ini+1, fin).toInt();
        }
      }
    }
    //Serial.println("***** FINALIZA RESPUESTA *****");
    //{SERVO:0, LED:0} 
    Serial.println(servo);
    Serial.println(led);
    client.stop();
  }
  //Serial.print(url);
}

void loop() {
  // put your main code here, to run repeatedly:
  buttonState = digitalRead(buttonPin);
  if (buttonState != lastButtonState) {
    if (buttonState == HIGH) {
      digitalWrite(LED, !HIGH);
      consumo++;                             
      Serial.println(consumo);
    } 
    else {
      digitalWrite(LED, !LOW);
                
    }
  }
  lastButtonState = buttonState;
  delay (50);
  method2();
  valvula.write(servo);
}
