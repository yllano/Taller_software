import firebase_admin
from firebase_admin import credentials, firestore
import os

class Config:
    # Ruta al archivo de credenciales que descargaste de Firebase
    CERTIFICATE_PATH = "serviceAccountKey.json"
    
    @staticmethod
    def init_firebase():
        if not firebase_admin._apps:
            cred = credentials.Certificate(Config.CERTIFICATE_PATH)
            firebase_admin.initialize_app(cred)
        return firestore.client()

# Instancia de la base de datos para usar en otros archivos
db = Config.init_firebase()