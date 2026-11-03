# pyright: reportMissingImports=false
import json
from datetime import datetime
from typing import Dict, Optional
from fastapi import FastAPI, Depends
from sqlalchemy.orm import Session
from pydantic import BaseModel

from bd import SessionLocal, SiteDado, SensorLeitura, init_db
from ml3 import prever_toxicidade, prever_gases, validar_contexto
from models import MLResultado

# modelos pydantic
class LeituraESP32Schema(BaseModel):
    temperatura: float
    umidade: float
    o2: Optional[float] = 20.5
    ph: Optional[float] = 7.0
    gases: Optional[Dict[str, float]] = {}

class SiteDadoSchema(BaseModel):
    composto_verde: str
    peso_verde_kg: float
    composto_marrom: str
    peso_marrom_kg: float
    temp_alvo_c: float
    umidade_alvo_pct: float

# pra startar a api
app = FastAPI(title="Ecovita API")

# dependências do banco
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

@app.on_event("startup")
def startup():
    init_db()

# as rotas do site
@app.get("/site_dados")
def listar_site_dados(db: Session = Depends(get_db)):
    return db.query(SiteDado).all()

@app.post("/site_dados")
def inserir_site_dado(dado: SiteDadoSchema, db: Session = Depends(get_db)):
    novo = SiteDado(
        composto_verde=dado.composto_verde,
        peso_verde_kg=dado.peso_verde_kg,
        composto_marrom=dado.composto_marrom,
        peso_marrom_kg=dado.peso_marrom_kg,
        temp_alvo_c=dado.temp_alvo_c,
        umidade_alvo_pct=dado.umidade_alvo_pct,
        data_registro=datetime.now()
    )
    db.add(novo)
    db.commit()
    db.refresh(novo)

    ml1_res = prever_toxicidade(novo, db)

    return {"inserido": novo, "ml1_resultado": ml1_res}

# rotas do esp
@app.post("/esp32/leitura")
def receber_leitura(dado: LeituraESP32Schema, db: Session = Depends(get_db)):
    nova = SensorLeitura(
        temperatura=dado.temperatura,
        umidade=dado.umidade,
        o2=dado.o2,
        ph=dado.ph,
        gases=json.dumps(dado.gases) if dado.gases else "{}",
        data_registro=datetime.now()
    )
    db.add(nova)
    db.commit()
    db.refresh(nova)

    ml2_res = prever_gases(nova, db)
    ml3_res = validar_contexto(nova, ml2_res, db)

    return {
        "leitura": nova,
        "ml2_resultado": ml2_res,
        "ml3_validacao": ml3_res
    }

@app.get("/esp32/leitura")
def listar_leituras(db: Session = Depends(get_db)):
    return db.query(SensorLeitura).all()

# as rotas dos mls
@app.get("/ml_resultados")
def listar_resultados(db: Session = Depends(get_db)):
    return db.query(MLResultado).all()