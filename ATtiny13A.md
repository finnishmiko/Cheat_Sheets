# ATtiny13A

### Add ATtiny to Arduino IDE
* Add [this file](https://mcudude.github.io/MicroCore/package_MCUdude_MicroCore_index.json) to _File - Settings  - Additional Boards Manager URLs_
* Then install it via _tools - board - Boards manager..._

	* Other boards are available [here](http://playground.arduino.cc/Main/ArduinoOnOtherAtmelChips)

### Use Arduino Uno to program ATtiny13A
* Program Arduino Uno with SW that can be found from Arduino IDE's _File - Examples - 11.Arduino ISP - ArduinoISP_

* Connect ATtiny to Arduino:

Arduino|AtTiny13A (pin)
---|---
3.3V|VCC (8)
GND|GND (4)	
D10|Reset (1)	
D11|MOSI (11)	
D12|MISO (12)	
D13|SCK (13)	

* Arduino IDE settings
	* Board: ATtiny13
	* BOD Disabled
	* Clock 1.2MHz
	* Programmer: Arduino AS ISP (_not ArduinoISP_)

* Upload sketch to ATtiny using _Sketch - Upload Using Programmer_


