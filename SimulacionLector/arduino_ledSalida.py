import serial
import time

# print("Intentando abrir COM4...")

arduino = serial.Serial('COM4', 9600, timeout=1)
time.sleep(2)

# print("Puerto abierto correctamente, enviando comando...")
arduino.write(b"OFF\n")

arduino.close()
# print("Comando enviado y puerto cerrado.")