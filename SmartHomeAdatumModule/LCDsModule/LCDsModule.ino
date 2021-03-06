#include <LiquidCrystal.h>
#include <PCD8544.h>

static PCD8544 lcdm;
LiquidCrystal lcd(12, 11, 5, 4, 3, 2);

char serianname[] = "6B7DE9KT" ;      // серийный номер устройства в сети
#define SerialTxControl 10   //RS485 Direction control
#define RS485Transmit    HIGH
#define RS485Receive     LOW  
char buffer[100];
char mode[4];                                 // Переменные для разбора буфера
char addres[10];                                // Переменные для разбора буфера
char vale1[14];                               // Переменные для разбора буфера
char vale2[14];                               // Переменные для разбора буфера
char vale3[14];                               // Переменные для разбора буфера
char vale4[4];                                // Переменные для разбора буфера
char modecontrol[4];                          // Переменные для разбора буфера


void setup() 
{
  
    Serial.begin(9600);
   pinMode(SerialTxControl, OUTPUT);    

   lcd.begin(84, 48);
  digitalWrite(SerialTxControl, RS485Transmit); 
  Serial.print("TST,");   Serial.print(serianname); Serial.println(",0,0,0,iBN,TST");
  delay(100); 
  digitalWrite(SerialTxControl, RS485Receive); 
  
   lcd.begin(16, 2);
  lcdm.begin(84, 48);
  
 

}

void loop() {

 digitalWrite(SerialTxControl, RS485Receive);  
      

  
   int x=0; if(Serial.available()){delay(100);
  while( Serial.available() && x< 99) { buffer[x++] = Serial.read();} buffer[x++]='\0';}
  if(x>0)
  {
  
  Serial.println(buffer);


  
     sscanf(buffer, "%[^','],%[^','],%[^','],%[^','],%[^','],%[^','],%s", &mode, &addres, &vale1, &vale2, &vale3, &vale4, &modecontrol);          
  
     
  
     if ((String)addres == serianname) {
     if ((String)mode == (String)modecontrol) {
       
       
     if ((String)mode == "TST")   
      {
  digitalWrite(SerialTxControl, RS485Transmit); 
 Serial.print("TST,");   Serial.print(serianname); Serial.println(",0,0,0,iBN,TST");
  delay(100); 
  digitalWrite(SerialTxControl, RS485Receive);   
      
     }
     
          if ((String)mode == "DIS")   //DIS,6B7DE9KT,1,Zeroo,0,iBN,DIS
      {  
 lcd.setCursor(0, atoi(vale1));
 lcd.print(vale2);
 lcdm.setCursor(0, atoi(vale1));
 lcdm.print(vale2);
  
     }
     
     
     
     }
     }
  }



}


