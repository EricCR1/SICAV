import serial
import time

# COM3 puede variar; revisa en el administrador de dispositivos
arduino = serial.Serial('COM3', 9600, timeout=1)
time.sleep(2)  # Esperar a que Arduino inicie

arduino.write(b"ON\n")
arduino.close()
