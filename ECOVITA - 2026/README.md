# Projeto Ecovita: Compostagem Inteligente

Sistema de monitoramento e controle automatizado para compostagem eletrônica com predição de Compostos Orgânicos Voláteis (COVs) usando Inteligência Artificial e IoT.

---

## 📋 Estrutura do Projeto

```text
ecovita/
├── api.py                          # API FastAPI (Backend & Integração IoT)
├── bd.py                           # Modelagem do Banco de Dados (SQLAlchemy / SQLite)
├── dash.py                         # Interface Web Interativa (Streamlit)
├── ml1_covs.py                     # Modelo ML-1 (Regressão de COVs e Toxicidade)
├── ml2_nariz.py                    # Modelo ML-2 (Nariz Eletrônico - Rede Neural Dual-Head)
├── ml3_contexto.py                 # Modelo ML-3 (Filtro Contextual e Estágios da Compostagem)
├── ecovita.db                      # Banco de dados SQLite
├── requirements.txt                # Dependências Python do projeto
├── arduino_mega/
│   └── arduino_mega.ino            # Firmware C++ do Arduino Mega (Coleta de Sensores & Atuadores)
├── esp32/
│   └── esp32_gateway.ino           # Firmware C++ do ESP32 (Gateway Wi-Fi / HTTP POST)
└── static/
    └── imgs/
        └── logo_ecovita.png        # Logo do projeto
```

---

## 🚀 Como Usar

### 1. Instalar Dependências

Crie e ative um ambiente virtual e instale as bibliotecas necessárias:

- **Windows (PowerShell):**
  ```powershell
  python -m venv venv
  .\venv\Scripts\Activate.ps1
  pip install -r requirements.txt
  ```

- **Linux / macOS:**
  ```bash
  python3 -m venv venv
  source venv/bin/activate
  pip install -r requirements.txt
  ```

### 2. Inicializar o Banco de Dados e Treinar os Modelos (Opcional)

Os modelos pré-treinados e o banco SQLite são inicializados automaticamente na execução, mas podem ser ajustados ou treinados novamente rodando os scripts dos modelos:

```bash
python ml1_covs.py
python ml2_nariz.py
python ml3_contexto.py
```

Isso gera os artefatos `.h5`, `.joblib` e escaladores para inferência em tempo real.

### 3. Iniciar a API FastAPI (Backend)

Em um terminal, execute a API que recebe as leituras do ESP32 e processa as requisições do painel:

```bash
uvicorn api:app --reload --host 0.0.0.0 --port 8000
```

A API estará disponível em: `http://localhost:8000`  
Documentação interativa Swagger: `http://localhost:8000/docs`

### 4. Iniciar o Painel Web (Streamlit)

Em outro terminal com o ambiente virtual ativo, inicie a interface gráfica:

```bash
streamlit run dash.py
```

Acesse no navegador: `http://localhost:8501`

---

## 🔧 Configuração e Hardware

### Integração Microcontroladores (IoT)

1. **Arduino Mega 2560:** Conectado aos sensores de gás (`MQ-135`, `MQ-4`, `MQ-136`, `MQ-3`), `DHT22` (temperatura/umidade), módulo de `pH` e atuadores (relés para bombas e aquecimento). Envia pacotes estruturados via `Serial1` para o ESP32.
2. **ESP32:** Atua como Gateway. Recebe os dados seriais do Mega, mede a umidade capacitiva do solo, formata a requisição em JSON e envia via `HTTP POST` para a rota `/esp32/leitura` da API.

---

## 📱 Páginas Disponíveis no Painel Web

1. **Início (`/`)** - Visão geral do projeto, equipe institucional e dados instantâneos.
2. **Dashboard (`/dashboard`)** - Monitoramento em tempo real com gráficos Plotly e tabela de dados do ESP32.
3. **Registrar Compostagem (`/compostagem`)** - Entrada de insumos (compostos verdes/marrons), cálculo automático da razão C/N e estimativa de eficiência.
4. **Análises & ML (`/analises`)** - Comparativos de desempenho, agrupamento via K-Means e avaliação dos modelos preditivos.
5. **Configurações (`/configuracoes`)** - Calibração de limites de alerta, intervalos de leitura e testes de integração de API.

---

## 🧠 Modelos de Inteligência Artificial

- **ML-1 (COVs & Toxicidade):** Redes Neurais e Random Forest para prever concentração de gases e classificar o nível de toxicidade (Baixa, Média, Alta).
- **ML-2 (Nariz Eletrônico):** Arquitetura Dual-Head no TensorFlow/Keras para estimar concentrações gasosas puras e indicadores derivados de impacto ambiental.
- **ML-3 (Filtro Contextual):** Classificação multiclasse do estágio da compostagem (*Inicial*, *Termofílica*, *Maturação*) e cálculo do índice de coerência ambiental.

---

## 🔐 Segurança e Conectividade

- Mapeamento ORM e validação de requisições rigorosa via **Pydantic Schemas** na API FastAPI.
- Tratamento de reconexão automática Wi-Fi nos microcontroladores.
- Suporte para exportação de relatórios em formato `.csv`.

---

## 🎨 Design e Interface

- Tema escuro personalizado focado na experiência de análise de dados (Data Science/IoT).
- Gráficos vetoriais dinâmicos alimentados pela biblioteca **Plotly**.
- Design responsivo e cards de status informativos (*Ótimo*, *Normal*, *Monitorar*).

---

## ⚠️ Aviso Legal

Este sistema é um **Trabalho de Conclusão de Curso (TCC)** e possui **caráter experimental e acadêmico**.

Resultados e recomendações geradas pelos modelos computacionais servem como apoio e triagem, devendo ser validados em campo.

---

## 👥 Autores e Orientadores

- **Marcelo Miranda de Oliveira Junior** (Desenvolvimento Hardware / IoT)
- **Murilo Romeu Teixeira** (Frontend / UX / Backend)

**Orientadora:** Prof.ª Simoni Machado  
**Coorientadores:** Prof. Leonardo Santana Benevides, Prof. José Henrique Lopes da Silva  

---

**Escola:** E.E.E.M. Brigadeiro José da Silva Paes — Curso Técnico em Informática Integrado ao Ensino Médio (Rio Grande, RS — 2026)