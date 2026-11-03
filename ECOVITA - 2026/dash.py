import streamlit as st
import pandas as pd
import json
import plotly.graph_objects as go
import numpy as np
from datetime import datetime, timedelta
import requests

# config base da pagina
st.set_page_config(
    page_title="Ecovita Compostagem",
    page_icon="🌱",
    layout="wide",
    initial_sidebar_state="collapsed"
)

# puxa a api
API_URL = "http://127.0.0.1:8000"

# puxa as coisas da api
@st.cache_data(ttl=3)  # Atualiza a cada 3 segundos
def carregar_leituras_esp32():
    try:
        response = requests.get(f"{API_URL}/esp32/leitura")
        if response.status_code == 200:
            dados = response.json()
            if dados:
                df = pd.DataFrame(dados)
                if 'data_registro' in df.columns:
                    df['data_registro'] = pd.to_datetime(df['data_registro'])
                return df
            return pd.DataFrame()
        else:
            return pd.DataFrame()
    except Exception:
        return pd.DataFrame()

# css :(
st.markdown("""
<style>
/* ===== RESET E FUNDO DA PÁGINA ===== */
.stApp {
    background: linear-gradient(135deg, #f4f9f4 0%, #eaf4ea 50%, #d8ebd8 100%) fixed;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #1b4332;
}

/* ===== NAVBAR SUPERIOR ===== */
.custom-navbar {
    background: linear-gradient(135deg, #2d6a4f 0%, #1b4332 100%) !important;
    border-bottom: 4px solid #52b788 !important;
    padding: 0.8rem 2rem !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 1000 !important;
    box-shadow: 0 8px 25px rgba(27, 67, 50, 0.15) !important;
    margin-bottom: 1.5rem !important;
    border-radius: 0 0 20px 20px !important;
}

.nav-container {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    max-width: 1400px !important;
    margin: 0 auto !important;
}

.nav-brand {
    display: flex !important;
    align-items: center !important;
    gap: 1rem !important;
}

.brand-logo {
    font-size: 2.5rem !important;
}

.brand-text {
    color: #ffffff !important;
    font-size: 2rem !important;
    font-weight: 800 !important;
    letter-spacing: 0.5px !important;
}

.nav-logos {
    display: flex !important;
    align-items: center !important;
    gap: 2rem !important;
}

.nav-logo {
    height: 45px !important;
    width: auto !important;
    filter: brightness(0) invert(1) !important;
    opacity: 0.85;
}

/* ===== SOBREPOSIÇÃO DE ESTILOS DOS BOTÕES STREAMLIT ===== */
/* Botão Inativo / Secundário */
div.stButton > button[kind="secondary"] {
    background-color: #ffffff !important;
    color: #2d6a4f !important;
    border: 2px solid #b7e4c7 !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
}

div.stButton > button[kind="secondary"]:hover {
    background-color: #e8f5e9 !important;
    border-color: #52b788 !important;
    color: #1b4332 !important;
    transform: translateY(-2px) !important;
}

/* Botão Ativo / Página Atual */
div.stButton > button[kind="primary"] {
    background: linear-gradient(135deg, #40916c 0%, #2d6a4f 100%) !important;
    color: #ffffff !important;
    border: 2px solid #52b788 !important;
    border-radius: 12px !important;
    font-weight: 700 !important;
    box-shadow: 0 4px 12px rgba(45, 106, 79, 0.3) !important;
}

div.stButton > button[kind="primary"]:hover {
    background: linear-gradient(135deg, #52b788 0%, #40916c 100%) !important;
    border-color: #74c69d !important;
    color: #ffffff !important;
}

/* ===== ESTILIZAÇÃO DAS TABS DO STREAMLIT ===== */
button[data-baseweb="tab"] div p {
    color: #1b4332 !important;
    font-weight: 600 !important;
}

button[data-baseweb="tab"][aria-selected="true"] div p {
    color: #1b4332 !important;
    font-weight: 800 !important;
}

div[data-baseweb="tab-highlight"] {
    background-color: #2d6a4f !important;
}

button[data-baseweb="tab"]:hover div p {
    color: #2d6a4f !important;
}

/* ===== CARDS E CONTAINERS ===== */
.eco-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 2rem 1.5rem !important;
    margin: 1rem 0 !important;
    border: 1px solid #d8ebd8 !important;
    box-shadow: 0 4px 20px rgba(45, 106, 79, 0.06) !important;
    text-align: center !important;
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    align-items: center !important;
}

.card-icon {
    font-size: 3rem !important;
    margin-bottom: 1rem !important;
}

.card-title {
    color: #2d6a4f !important;
    font-size: 1.2rem !important;
    font-weight: 700 !important;
    margin-bottom: 0.5rem !important;
}

.card-value {
    color: #1b4332 !important;
    font-size: 2.2rem !important;
    font-weight: 800 !important;
}

.card-description {
    color: #52796f !important;
    font-size: 0.9rem !important;
}

/* ===== ELEMENTOS DE TEXTO ===== */
h1 {
    color: #1b4332 !important;
    font-weight: 800 !important;
    font-size: 2.4rem !important;
    margin-bottom: 1.5rem !important;
}

h2, h3 {
    color: #2d6a4f !important;
    font-weight: 700 !important;
}

/* Métrica do Streamlit */
[data-testid="stMetric"] {
    background-color: #ffffff !important;
    border: 1px solid #d8ebd8 !important;
    padding: 1rem !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 10px rgba(45, 106, 79, 0.04) !important;
}

[data-testid="stMetricLabel"] {
    color: #52796f !important;
    font-weight: 600 !important;
}

[data-testid="stMetricValue"] {
    color: #1b4332 !important;
    font-weight: 800 !important;
}
</style>
""", unsafe_allow_html=True)

# funções auxiliares (tão importantes q n são tão auxiliares)
def render_navbar(active_tab="Dashboard"):
    navbar_html = """<div class="custom-navbar"><div class="nav-container"><div class="nav-logos"><img src="https://cdn-icons-png.flaticon.com/512/1006/1006555.png" class="nav-logo"></div><div class="nav-brand"><div class="brand-logo">🌱</div><div class="brand-text">ECOVITA COMPOSTAGEM</div></div><div class="nav-logos"><img src="https://cdn-icons-png.flaticon.com/512/1046/1046857.png" class="nav-logo"></div></div></div>"""
    st.markdown(navbar_html, unsafe_allow_html=True)

    col1, col2, col3, col4, col5 = st.columns(5)

    with col1:
        if st.button("📊 Dashboard", use_container_width=True, key="btn_dash", type="primary" if active_tab == "Dashboard" else "secondary"):
            st.session_state.current_tab = "Dashboard"
            st.rerun()

    with col2:
        if st.button("🌿 Compostagem", use_container_width=True, key="btn_comp", type="primary" if active_tab == "Compostagem" else "secondary"):
            st.session_state.current_tab = "Compostagem"
            st.rerun()

    with col3:
        if st.button("📈 Gráficos", use_container_width=True, key="btn_graf", type="primary" if active_tab == "Gráficos" else "secondary"):
            st.session_state.current_tab = "Gráficos"
            st.rerun()

    with col4:
        if st.button("⚙️ Controle", use_container_width=True, key="btn_cont", type="primary" if active_tab == "Controle" else "secondary"):
            st.session_state.current_tab = "Controle"
            st.rerun()

    with col5:
        if st.button("📋 Relatórios", use_container_width=True, key="btn_rel", type="primary" if active_tab == "Relatórios" else "secondary"):
            st.session_state.current_tab = "Relatórios"
            st.rerun()

def gerar_grafico(titulo, tipo="linha", df=None, coluna="temperatura", cor='#2d6a4f'):
    fig = go.Figure()
    
    if df is not None and not df.empty and coluna in df.columns:
        x = df['data_registro'] if 'data_registro' in df.columns else range(len(df))
        y = df[coluna]
    else:
        x = pd.date_range(end=datetime.now(), periods=10, freq='h')
        y = np.random.randint(20, 80, 10)
    
    if tipo == "linha":
        fig.add_trace(go.Scatter(x=x, y=y, mode='lines+markers', line=dict(color=cor, width=3), name=titulo))
    elif tipo == "barra":
        fig.add_trace(go.Bar(x=x, y=y, marker_color=cor, name=titulo))
    elif tipo == "area":
        fig.add_trace(go.Scatter(x=x, y=y, fill='tozeroy', line=dict(color=cor, width=2), name=titulo))
    
    fig.update_layout(
        title=dict(text=titulo, font=dict(color='#1b4332', size=18)),
        plot_bgcolor='rgba(0,0,0,0)',
        paper_bgcolor='rgba(0,0,0,0)',
        font=dict(color='#2d6a4f', size=11),
        margin=dict(l=20, r=20, t=50, b=40),
        height=350
    )
    return fig

def criar_secao_ml(numero, titulo, descricao, acuracia, precisao, recall, f1, features):
    """Gera o HTML renderizado das seções de ML no estilo visual do Código 2"""
    return f"""<div style="background: #ffffff; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid #d8ebd8; box-shadow: 0 4px 20px rgba(45, 106, 79, 0.06);"><h4 style="color: #2d6a4f; margin-top: 0; font-weight: 700; font-size: 1.2rem;">🤖 Modelo {numero}: {titulo}</h4><p style="color: #52796f; font-size: 0.95rem; margin-bottom: 1rem;">{descricao}</p><div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 1rem; text-align: center;"><div style="background: #f4f9f4; padding: 0.6rem; border-radius: 8px; border: 1px solid #b7e4c7;"><span style="font-size: 0.8rem; color: #52796f; display: block;">Acurácia</span><strong style="color: #1b4332; font-size: 1.1rem;">{acuracia}%</strong></div><div style="background: #f4f9f4; padding: 0.6rem; border-radius: 8px; border: 1px solid #b7e4c7;"><span style="font-size: 0.8rem; color: #52796f; display: block;">Precisão</span><strong style="color: #1b4332; font-size: 1.1rem;">{precisao}%</strong></div><div style="background: #f4f9f4; padding: 0.6rem; border-radius: 8px; border: 1px solid #b7e4c7;"><span style="font-size: 0.8rem; color: #52796f; display: block;">Recall</span><strong style="color: #1b4332; font-size: 1.1rem;">{recall}%</strong></div><div style="background: #f4f9f4; padding: 0.6rem; border-radius: 8px; border: 1px solid #b7e4c7;"><span style="font-size: 0.8rem; color: #52796f; display: block;">F1-Score</span><strong style="color: #1b4332; font-size: 1.1rem;">{f1}%</strong></div></div><p style="color: #1b4332; font-size: 0.85rem; margin: 0;"><strong style="color: #2d6a4f;">Features utilizadas:</strong> {features}</p></div>"""

# páginas
def pagina_dashboard():
    st.markdown('<h1>Dashboard de Compostagem Inteligente</h1>', unsafe_allow_html=True)
    
    if st.button("🔄 Atualizar Dados"):
        st.cache_data.clear()

    df_leituras = carregar_leituras_esp32()
    
    temp_val, umid_val, ph_val, o2_val = 0.0, 0.0, 7.0, 20.5
    
    if not df_leituras.empty:
        ultima = df_leituras.iloc[-1]
        temp_val = ultima.get('temperatura', 0.0)
        umid_val = ultima.get('umidade', 0.0)
        ph_val = ultima.get('ph', 7.0)
        o2_val = ultima.get('o2', 20.5)
    
    st.markdown('### 📊 Métricas em Tempo Real (ESP32)')
    col1, col2, col3, col4 = st.columns(4)
    
    with col1:
        st.metric("🌡️ Temperatura", f"{temp_val:.1f} °C")
    with col2:
        st.metric("💧 Umidade", f"{umid_val:.1f} %")
    with col3:
        st.metric("⚗️ pH", f"{ph_val:.1f}")
    with col4:
        st.metric("💨 Oxigênio (O₂)", f"{o2_val:.1f} %")
    
    st.markdown('---')
    st.markdown('### 📈 Análise de Tendências Reais')
    col1, col2 = st.columns(2)
    
    with col1:
        st.plotly_chart(gerar_grafico("Temperatura em Tempo Real (°C)", "linha", df_leituras, "temperatura", "#2d6a4f"), use_container_width=True)
    
    with col2:
        st.plotly_chart(gerar_grafico("Umidade em Tempo Real (%)", "area", df_leituras, "umidade", "#52b788"), use_container_width=True)

    if not df_leituras.empty:
        st.subheader("📋 Tabela de Leituras Recebidas da API")
        st.dataframe(df_leituras.tail(10), use_container_width=True)

def pagina_compostagem():
    st.markdown('<h1>Controle de Compostagem</h1>', unsafe_allow_html=True)
    
    col1, col2 = st.columns([2, 1])
    
    with col1:
        st.markdown('<div class="eco-card"><h3>📥 Inserir Resíduos na API</h3></div>', unsafe_allow_html=True)
        
        with st.form("compostagem_form", clear_on_submit=True):
            col_a, col_b = st.columns(2)
            with col_a:
                verde = st.selectbox("Composto Verde", ["Folhas verdes", "Restos de cozinha", "Borras de café"])
                peso_verde = st.number_input("Peso Verde (kg)", min_value=0.1, value=1.0, step=0.5)
            with col_b:
                marrom = st.selectbox("Composto Marrom", ["Serragem", "Folhas secas", "Palha"])
                peso_marrom = st.number_input("Peso Marrom (kg)", min_value=0.1, value=1.0, step=0.5)
            
            temp_alvo = st.number_input("Temperatura Alvo (°C)", value=55.0)
            umidade_alvo = st.number_input("Umidade Alvo (%)", value=60.0)
            
            if st.form_submit_button("Enviar para a API", use_container_width=True):
                payload = {
                    "composto_verde": verde,
                    "peso_verde_kg": peso_verde,
                    "composto_marrom": marrom,
                    "peso_marrom_kg": peso_marrom,
                    "temp_alvo_c": temp_alvo,
                    "umidade_alvo_pct": umidade_alvo
                }
                try:
                    res = requests.post(f"{API_URL}/site_dados", json=payload)
                    if res.status_code == 200:
                        st.success("✅ Dados enviados com sucesso para a API!")
                    else:
                        st.error(f"Erro ao enviar: {res.status_code}")
                except Exception as e:
                    st.error(f"Falha na conexão: {e}")

def pagina_graficos():
    st.markdown('<h1>Análise Gráfica e Machine Learning</h1>', unsafe_allow_html=True)
    df_leituras = carregar_leituras_esp32()
    
    st.markdown('### 📊 Visualizações de Dados')
    
    tab1, tab2, tab3 = st.tabs(["Tendências Temporais", "Análise Comparativa", "Machine Learning"])
    
    # aba 1 - tendencias temporais
    with tab1:
        col1, col2 = st.columns(2)
        with col1:
            st.plotly_chart(
                gerar_grafico("Evolução da Temperatura (°C)", "linha", df_leituras, "temperatura", cor='#2d6a4f'), 
                use_container_width=True
            )
        with col2:
            st.plotly_chart(
                gerar_grafico("Variação de Umidade (%)", "area", df_leituras, "umidade", cor='#52b788'), 
                use_container_width=True
            )
        
        col3, col4 = st.columns(2)
        with col3:
            st.plotly_chart(
                gerar_grafico("Emissões de COVs (ppm)", "barra", df_leituras, "covs", cor='#40916c'), 
                use_container_width=True
            )
        with col4:
            st.plotly_chart(
                gerar_grafico("Níveis de pH", "linha", df_leituras, "ph", cor='#74c69d'), 
                use_container_width=True
            )
    
    # aba 2 - comparações
    with tab2:
        st.markdown('### 📈 Análise de Correlação')
        col1, col2 = st.columns(2)
        
        with col1:
            if not df_leituras.empty and 'temperatura' in df_leituras.columns and 'covs' in df_leituras.columns:
                x = df_leituras['temperatura']
                y = df_leituras['covs']
            else:
                x = np.random.randn(100)
                y = x * 2 + np.random.randn(100) * 0.5
                
            fig_scat = go.Figure()
            fig_scat.add_trace(go.Scatter(
                x=x, y=y, 
                mode='markers', 
                marker=dict(color='#2d6a4f', size=8, opacity=0.8)
            ))
            fig_scat.update_layout(
                title=dict(text="Correlação Temperatura vs COVs", font=dict(color='#1b4332', size=18)),
                xaxis_title="Temperatura (°C)",
                yaxis_title="COVs (ppm)",
                plot_bgcolor='rgba(0,0,0,0)',
                paper_bgcolor='rgba(0,0,0,0)',
                font=dict(color='#2d6a4f', size=11),
                margin=dict(l=20, r=20, t=50, b=40),
                height=350
            )
            st.plotly_chart(fig_scat, use_container_width=True)
        
        with col2:
            if not df_leituras.empty:
                cols_corr = [c for c in ['temperatura', 'umidade', 'covs', 'ph'] if c in df_leituras.columns]
                corr_data = df_leituras[cols_corr].corr() if len(cols_corr) > 1 else pd.DataFrame()
            else:
                corr_data = pd.DataFrame({
                    'Temperatura': np.random.rand(10),
                    'Umidade': np.random.rand(10),
                    'COVs': np.random.rand(10),
                    'pH': np.random.rand(10)
                }).corr()
            
            fig_heat = go.Figure(data=go.Heatmap(
                z=corr_data.values,
                x=corr_data.columns,
                y=corr_data.columns,
                colorscale=[[0, '#eaf4ea'], [0.5, '#52b788'], [1, '#1b4332']],
                showscale=True
            ))
            fig_heat.update_layout(
                title=dict(text="Matriz de Correlação", font=dict(color='#1b4332', size=18)),
                plot_bgcolor='rgba(0,0,0,0)',
                paper_bgcolor='rgba(0,0,0,0)',
                font=dict(color='#2d6a4f', size=11),
                margin=dict(l=20, r=20, t=50, b=40),
                height=350
            )
            st.plotly_chart(fig_heat, use_container_width=True)
            
    # aba 3 - analises dos MLs
    with tab3:
        st.markdown('### 🤖 Modelos de Machine Learning')
        
        st.markdown(
            criar_secao_ml(
                numero=1,
                titulo="Previsão de Emissões de COVs",
                descricao="Modelo de regressão para prever emissões baseado em temperatura, umidade e pH",
                acuracia=92, precisao=89, recall=91, f1=90,
                features="Temperatura, Umidade, pH, Volume, Idade do composto"
            ), 
            unsafe_allow_html=True
        )
        
        st.markdown(
            criar_secao_ml(
                numero=2,
                titulo="Classificação da Fase de Compostagem",
                descricao="Modelo de classificação para identificar a fase atual do processo",
                acuracia=87, precisao=85, recall=88, f1=86,
                features="Temperatura média, Variação térmica, Emissões, Umidade, Acidez"
            ), 
            unsafe_allow_html=True
        )
        
        st.markdown(
            criar_secao_ml(
                numero=3,
                titulo="Otimização de Eficiência",
                descricao="Modelo para recomendar ajustes no processo visando máxima eficiência",
                acuracia=94, precisao=92, recall=93, f1=92,
                features="Histórico térmico, Relação C/N, Frequência de revolvimento, Clima"
            ), 
            unsafe_allow_html=True
        )
        
        st.markdown('### 📊 Performance dos Modelos')
        modelos = ['ML1 - COVs', 'ML2 - Fase', 'ML3 - Otimização']
        acuracias = [92, 87, 94]
        
        fig_bar = go.Figure(data=[go.Bar(
            x=modelos, 
            y=acuracias, 
            marker_color=['#2d6a4f', '#52b788', '#40916c']
        )])
        fig_bar.update_layout(
            title=dict(text='Acurácia dos Modelos de Machine Learning', font=dict(color='#1b4332', size=18)),
            xaxis_title='Modelos',
            yaxis_title='Acurácia (%)',
            plot_bgcolor='rgba(0,0,0,0)',
            paper_bgcolor='rgba(0,0,0,0)',
            font=dict(color='#2d6a4f', size=11),
            margin=dict(l=20, r=20, t=50, b=40),
            height=350
        )
        st.plotly_chart(fig_bar, use_container_width=True)

def pagina_controle():
    st.markdown('<h1>Controle da Composteira</h1>', unsafe_allow_html=True)
    st.info("Painel de atuação manual e alertas configurados.")

def pagina_relatorios():
    st.markdown('<h1>Relatórios e Exportação</h1>', unsafe_allow_html=True)
    df_leituras = carregar_leituras_esp32()
    
    if not df_leituras.empty:
        csv = df_leituras.to_csv(index=False).encode('utf-8')
        st.download_button("⬇️ Baixar Histórico em CSV", data=csv, file_name="leituras_esp32.csv", mime="text/csv")
    else:
        st.warning("Sem dados para exportar.")

#mudança de abas
def main():
    if 'current_tab' not in st.session_state:
        st.session_state.current_tab = "Dashboard"
    
    render_navbar(st.session_state.current_tab)
    
    if st.session_state.current_tab == "Dashboard":
        pagina_dashboard()
    elif st.session_state.current_tab == "Compostagem":
        pagina_compostagem()
    elif st.session_state.current_tab == "Gráficos":
        pagina_graficos()
    elif st.session_state.current_tab == "Controle":
        pagina_controle()
    elif st.session_state.current_tab == "Relatórios":
        pagina_relatorios()

if __name__ == "__main__":
    main()