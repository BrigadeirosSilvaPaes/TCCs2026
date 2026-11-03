from flask import Flask, render_template, request, jsonify

app = Flask(__name__)

# ==========================
# HUB
# ==========================

@app.route("/")
def hub():
    return render_template("index.html")

# ==========================
# CARRINHO
# ==========================

@app.route("/carrinho")
def carrinho():
    return render_template("carrinho.html")

# ==========================
# DIAGNÓSTICO
# ==========================

@app.route("/diagnostico")
def diagnostico():
    return render_template("diagnostico.html")


# ==========================
# PEÇAS
# ==========================

@app.route("/pecas")
def pecas():
    return render_template("pecas.html")


# ==========================
# MECÂNICOS
# ==========================

@app.route("/mecanicos")
def mecanicos():
    return render_template("mecanicos.html")


# ==========================
# TUTORIAIS
# ==========================

@app.route("/tutoriais")
def tutoriais():
    return render_template("tutoriais.html")


# ==========================
# IA DO DIAGNÓSTICO
# ==========================

@app.route("/analisar", methods=["POST"])
def analisar():

    dados = request.get_json()

    problema = dados["problema"].lower()

    resposta = {
        "problema": "Não foi possível identificar.",
        "causa": "Descreva melhor o problema.",
        "gravidade": "Baixa",
        "confianca": "40%",
        "recomendacao": "Procure uma oficina para uma inspeção."
    }

    # BATERIA
    if any(p in problema for p in [
        "não liga",
        "nao liga",
        "bateria",
        "arranque"
    ]):

        resposta = {
            "problema": "Possível bateria descarregada ou defeito no motor de partida.",
            "causa": "A bateria pode estar sem carga ou o motor de arranque pode apresentar falha.",
            "gravidade": "Média",
            "confianca": "93%",
            "recomendacao": "Verifique a carga da bateria e o sistema de partida."
        }

    # SUPERAQUECIMENTO
    elif any(p in problema for p in [
        "esquenta",
        "superaquece",
        "temperatura",
        "fervendo"
    ]):

        resposta = {
            "problema": "Possível superaquecimento do motor.",
            "causa": "Baixo nível do líquido de arrefecimento, radiador ou bomba d'água.",
            "gravidade": "Alta",
            "confianca": "95%",
            "recomendacao": "Pare o veículo e não continue dirigindo até verificar o sistema."
        }

    # INJEÇÃO
    elif any(p in problema for p in [
        "injeção",
        "injecao",
        "luz da injeção",
        "luz do motor"
    ]):

        resposta = {
            "problema": "Falha no sistema de injeção eletrônica.",
            "causa": "Sensor, vela, bobina ou combustível de baixa qualidade.",
            "gravidade": "Média",
            "confianca": "90%",
            "recomendacao": "Realize um diagnóstico com scanner automotivo."
        }

    # FREIOS
    elif any(p in problema for p in [
        "freio",
        "freios",
        "pedal"
    ]):

        resposta = {
            "problema": "Possível desgaste no sistema de freios.",
            "causa": "Pastilhas gastas, discos ou fluido de freio.",
            "gravidade": "Alta",
            "confianca": "92%",
            "recomendacao": "Evite utilizar o veículo até verificar os freios."
        }

    # BARULHOS
    elif any(p in problema for p in [
        "barulho",
        "ruído",
        "ruido",
        "estalando"
    ]):

        resposta = {
            "problema": "Ruído mecânico identificado.",
            "causa": "Pode estar relacionado à suspensão, rolamentos ou correias.",
            "gravidade": "Média",
            "confianca": "82%",
            "recomendacao": "Faça uma inspeção para localizar a origem do ruído."
        }

    return jsonify(resposta)


if __name__ == "__main__":
    app.run(debug=True)