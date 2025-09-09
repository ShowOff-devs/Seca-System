import asyncio
import websockets
import json
import paho.mqtt.client as mqtt

# Store connected WebSocket clients
clients = set()

# MQTT settings
MQTT_BROKER = "192.168.29.7"  # or your Mosquitto server IP
MQTT_PORT = 1883
MQTT_TOPIC = "rfid/scans"

# When a message is received from MQTT
def on_message(client, userdata, msg):
    try:
        data = json.loads(msg.payload.decode())
        rfid_no = data.get('rfid_no')
        if rfid_no:
            print(f"Received from MQTT: {rfid_no}")
            # Broadcast to all WebSocket clients
            asyncio.run(broadcast(json.dumps({"rfid_no": rfid_no})))
    except Exception as e:
        print("Error:", e)

# Broadcast to all connected WebSocket clients
async def broadcast(message):
    to_remove = set()
    for ws in clients:
        try:
            await ws.send(message)
        except:
            to_remove.add(ws)
    for ws in to_remove:
        clients.remove(ws)

# Handle new WebSocket connections
async def ws_handler(websocket, path):
    clients.add(websocket)
    print("WebSocket client connected")
    try:
        while True:
            await asyncio.sleep(1)
    except:
        pass
    finally:
        clients.remove(websocket)
        print("WebSocket client disconnected")

# Set up MQTT client
mqtt_client = mqtt.Client()
mqtt_client.on_message = on_message
mqtt_client.connect(MQTT_BROKER, MQTT_PORT, 60)
mqtt_client.subscribe(MQTT_TOPIC)
mqtt_client.loop_start()

# Start WebSocket server
async def main():
    async with websockets.serve(ws_handler, "0.0.0.0", 8081):
        print("WebSocket server started on ws://0.0.0.0:8081")
        await asyncio.Future()  # run forever

asyncio.run(main()) 