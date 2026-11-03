<?php

/*
|--------------------------------------------------------------------------
| CATÁLOGO DO MANUAL JAVA
|--------------------------------------------------------------------------
|
| Este arquivo contém apenas dados internos do Manual de Java.
| O campo "conteudo" possui HTML confiável mantido pelo próprio projeto.
|
| Não utilizar dados enviados por usuários diretamente neste arquivo.
|
*/

return [
    [
        "id" => "fundamentos",
        "titulo" => "Fundamentos da linguagem",
        "icone" => "☕",
        "descricao" => "Conceitos essenciais para compreender a sintaxe, os tipos e o fluxo de execução em Java.",
        "nivel" => "Fundamental",
        "topicos" => [
            [
                "id" => "o-que-e-java",
                "titulo" => "O que é Java?",
                "descricao" => "Java é uma linguagem de programação de alto nível, criada em 1995 por James Gosling na Sun Microsystems, atualmente mantida pela Oracle. É orientada a objetos, fortemente tipada, e multiplataforma.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_01'
<h3>Teoria:</h3>
<p>Java é uma linguagem de programação de alto nível, criada em 1995 por James Gosling na Sun Microsystems, atualmente mantida pela Oracle. É orientada a objetos, fortemente tipada, e multiplataforma.</p>
<h3>Análise e lógica:</h3>
<p>Java foi projetada para ser segura, robusta e escalável. A JVM permite portabilidade e isolamento do código do sistema operacional. O paradigma orientado a objetos ajuda a modularizar e organizar sistemas complexos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Seguir convenções de nomenclatura.</li>
<li>Escrever código modular e documentado.</li>
<li>Evitar código duplicado, reutilizando classes e métodos.</li>
</ul>
<h3>Analogia:</h3>
<p>Java é como LEGO: peças padronizadas (classes) que podem ser combinadas para criar qualquer estrutura (objetos).</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>public class HelloWorld {
    public static void main(String[] args) {
        System.out.println("Olá, Java!");
    }
}</code></pre>
CONTEUDO_01_01,
            ],
            [
                "id" => "por-que-java-e-tao-utilizada",
                "titulo" => "Por que Java é tão utilizada?",
                "descricao" => "Java combina portabilidade, estabilidade, performance e uma comunidade enorme, sendo confiável para sistemas críticos e de grande escala.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_02'
<h3>Teoria:</h3>
<p>Java combina portabilidade, estabilidade, performance e uma comunidade enorme, sendo confiável para sistemas críticos e de grande escala.</p>
<h3>Análise e lógica:</h3>
<p>O ecossistema de Java inclui frameworks, bibliotecas e suporte corporativo. A JVM utiliza técnicas como compilação JIT para otimizar partes do código durante a execução. O desempenho final depende da aplicação, da JVM, do hardware e da carga de trabalho.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar frameworks consolidados (Spring, Hibernate).</li>
<li>Manter modularidade e reusabilidade.</li>
<li>Escrever testes automatizados.</li>
</ul>
<h3>Analogia:</h3>
<p>Java é como um carro confiável: mesmo em estradas diferentes, ele roda sem problemas e suporta cargas pesadas.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>// Aplicações corporativas, Android, servidores
// Exemplo conceitual sobre o uso de Java</code></pre>
CONTEUDO_01_02,
            ],
            [
                "id" => "tipos-de-dados",
                "titulo" => "Tipos de Dados",
                "descricao" => "Tipos de dados definem que valores variáveis podem armazenar. São divididos em primitivos e referência.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_03'
<h3>Teoria:</h3>
<p>Tipos de dados definem que valores variáveis podem armazenar. São divididos em primitivos e referência.</p>
<h3>Análise e lógica:</h3>
<p>Tipos primitivos representam valores diretamente, enquanto tipos de referência apontam para objetos e permitem estruturas mais complexas. O uso de memória e o desempenho dependem do contexto e das otimizações da JVM.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Escolher tipos adequados ao tamanho e necessidade.</li>
<li>Evitar casting desnecessário.</li>
</ul>
<h3>Analogia:</h3>
<p>Variáveis são caixas, tipos de dados determinam o que cabe dentro delas.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>int idade = 30;
double altura = 1.75;
boolean ativo = true;
String nome = "João";
int[] numeros = {1,2,3};</code></pre>
CONTEUDO_01_03,
            ],
            [
                "id" => "operadores",
                "titulo" => "Operadores",
                "descricao" => "Operadores permitem realizar cálculos, comparações e operações lógicas.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_04'
<h3>Teoria:</h3>
<p>Operadores permitem realizar cálculos, comparações e operações lógicas.</p>
<h3>Análise e lógica:</h3>
<p>Operadores são essenciais para controlar o fluxo de execução e manipular valores. Lógica: expressar decisões e cálculos de forma clara.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar operadores complexos em uma única expressão.</li>
<li>Usar parênteses para clareza.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>int a = 5, b = 10;
if(a &lt; b &amp;&amp; b &gt; 0) {
    System.out.println("Condição verdadeira!");
}</code></pre>
CONTEUDO_01_04,
            ],
            [
                "id" => "estruturas-de-controle",
                "titulo" => "Estruturas de Controle",
                "descricao" => "Estruturas de controle permitem decidir e repetir trechos de código.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_05'
<h3>Teoria:</h3>
<p>Estruturas de controle permitem decidir e repetir trechos de código.</p>
<h3>Análise e lógica:</h3>
<p>If/else permite ramificar decisões; loops permitem repetição baseada em condições, evitando código duplicado.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar loops infinitos.</li>
<li>Deixar condições claras e simples.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>for(int i=0; i&lt;5; i++) {
    System.out.println("Número:" + i);
}</code></pre>
CONTEUDO_01_05,
            ],
            [
                "id" => "metodos",
                "titulo" => "Métodos",
                "descricao" => "Métodos definem comportamentos que objetos podem realizar. Eles encapsulam lógica e operações relacionadas a atributos ou outras funcionalidades.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_06'
<h3>Teoria:</h3>
<p>Métodos definem comportamentos que objetos podem realizar. Eles encapsulam lógica e operações relacionadas a atributos ou outras funcionalidades.</p>
<h3>Análise e lógica:</h3>
<p>O uso de métodos promove modularidade, evita repetição de código e facilita manutenção. Lógica: encapsular ações relacionadas ao estado do objeto.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Um método = uma ação clara.</li>
<li>Evitar métodos longos ou com múltiplas responsabilidades.</li>
</ul>
<h3>Analogia:</h3>
<p>Método = função da casa, como ligar luz ou abrir porta.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>fusca.buzinar(); // Fusca diz: BEEP BEEP!

public void ligarFarol() {
    System.out.println("Farol ligado!");
}</code></pre>
CONTEUDO_01_06,
            ],
            [
                "id" => "manipulacao-de-strings",
                "titulo" => "Manipulação de Strings",
                "descricao" => "Strings são imutáveis em Java. Para manipulação eficiente, usamos StringBuilder ou StringBuffer.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_07'
<h3>Teoria:</h3>
<p>Strings são imutáveis em Java. Para manipulação eficiente, usamos StringBuilder ou StringBuffer.</p>
<h3>Análise e lógica:</h3>
<p>Use StringBuilder em cenários de concatenação frequente para melhorar performance.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Prefira StringBuilder para concatenações frequentes em código de uma única thread.</li>
<li>Use StringBuffer apenas quando a sincronização entre threads for realmente necessária.</li>
<li>Evite concatenação excessiva de Strings imutáveis.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>String texto = "Olá";
StringBuilder sb = new StringBuilder(texto);
sb.append(" Java!");
System.out.println(sb.toString()); // Olá Java!</code></pre>
CONTEUDO_01_07,
            ],
            [
                "id" => "enums",
                "titulo" => "Enums",
                "descricao" => "Enums representam constantes nomeadas, facilitando legibilidade e segurança de tipos.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_08'
<h3>Teoria:</h3>
<p>Enums representam constantes nomeadas, facilitando legibilidade e segurança de tipos.</p>
<h3>Análise e lógica:</h3>
<p>Evita strings ou números mágicos, tornando o código mais confiável.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar enums para valores fixos e previsíveis.</li>
<li>Evitar usar enums para dados que mudam com frequência.</li>
</ul>
<h3>Analogia:</h3>
<p>Enum é como uma lista de opções fixas de um menu.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>enum Dia { SEG, TER, QUA, QUI, SEX }

Dia hoje = Dia.SEG;
System.out.println(hoje); // SEG</code></pre>
CONTEUDO_01_08,
            ],
            [
                "id" => "conversao-de-tipos-casting",
                "titulo" => "Conversão de tipos (casting)",
                "descricao" => "Casting permite converter tipos de dados. Pode ser implícito ou explícito.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_09'
<h3>Teoria:</h3>
<p>Casting permite converter tipos de dados. Pode ser implícito ou explícito.</p>
<h3>Análise e lógica:</h3>
<p>Evitar perda de dados em conversões explícitas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar casting desnecessário.</li>
<li>Preferir tipos compatíveis.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>int a = 10;
double b = a; // implícito
double c = 9.78;
int d = (int)c; // explícito</code></pre>
CONTEUDO_01_09,
            ],
            [
                "id" => "api-de-data-e-hora",
                "titulo" => "API de Data e Hora",
                "descricao" => "Classes como LocalDate, LocalTime e ZonedDateTime substituem Date/Calendar.",
                "nivel" => "Fundamental",
                "conteudo" => <<<'CONTEUDO_01_10'
<h3>Teoria:</h3>
<p>Classes como LocalDate, LocalTime e ZonedDateTime substituem Date/Calendar.</p>
<h3>Análise e lógica:</h3>
<p>API moderna, imutável e thread-safe.</p>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.time.*;

LocalDate hoje = LocalDate.now();
LocalTime agora = LocalTime.now();
ZonedDateTime zdt = ZonedDateTime.now();
System.out.println(hoje + " " + agora + " " + zdt);</code></pre>
CONTEUDO_01_10,
            ],
        ],
    ],
    [
        "id" => "poo",
        "titulo" => "Programação Orientada a Objetos",
        "icone" => "🧩",
        "descricao" => "Classes, objetos, encapsulamento, herança, polimorfismo e contratos de código.",
        "nivel" => "Intermediário",
        "topicos" => [
            [
                "id" => "classes",
                "titulo" => "Classes",
                "descricao" => "Uma classe define atributos (estado) e métodos (comportamento). Ela é um molde para objetos, abstraindo conceitos do mundo real.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_01'
<h3>Teoria:</h3>
<p>Uma classe define atributos (estado) e métodos (comportamento). Ela é um molde para objetos, abstraindo conceitos do mundo real.</p>
<h3>Análise e lógica:</h3>
<p>Classes ajudam a organizar código, definir responsabilidades e permitir reutilização. A lógica de orientação a objetos separa definição (classe) de instância (objeto).</p>
<h3>Boas práticas:</h3>
<ul>
<li>Uma classe deve ter uma única responsabilidade.</li>
<li>Encapsular atributos com getters/setters.</li>
<li>Evitar classes gigantes e métodos longos.</li>
</ul>
<h3>Analogia:</h3>
<p>Classe = projeto arquitetônico; objeto = casa construída.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>public class Carro {
    private String modelo;
    private int ano;

    public Carro(String modelo, int ano) {
        this.modelo = modelo;
        this.ano = ano;
    }

    public void buzinar() {
        System.out.println(modelo + " diz: BEEP BEEP!");
    }

    public String getModelo() { return modelo; }
    public int getAno() { return ano; }
}</code></pre>
CONTEUDO_02_01,
            ],
            [
                "id" => "objetos",
                "titulo" => "Objetos",
                "descricao" => "Objetos são instâncias concretas de classes. Cada objeto tem seu próprio estado e comportamento, permitindo manipulação individual.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_02'
<h3>Teoria:</h3>
<p>Objetos são instâncias concretas de classes. Cada objeto tem seu próprio estado e comportamento, permitindo manipulação individual.</p>
<h3>Análise e lógica:</h3>
<p>Objetos implementam a lógica de classes de forma prática. A manipulação de objetos é central para programação orientada a objetos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Criar objetos com construtores claros.</li>
<li>Evitar acesso direto a atributos.</li>
</ul>
<h3>Analogia:</h3>
<p>Se classe = projeto de casa, objeto = casa construída.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>Carro fusca = new Carro("Fusca", 1985);
Carro gol = new Carro("Gol", 2010);

fusca.buzinar(); // Fusca diz: BEEP BEEP!
gol.buzinar();   // Gol diz: BEEP BEEP!</code></pre>
CONTEUDO_02_02,
            ],
            [
                "id" => "atributos",
                "titulo" => "Atributos",
                "descricao" => "Atributos representam características de objetos. Cada instância de uma classe pode ter valores diferentes para seus atributos.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_03'
<h3>Teoria:</h3>
<p>Atributos representam características de objetos. Cada instância de uma classe pode ter valores diferentes para seus atributos.</p>
<h3>Análise e lógica:</h3>
<p>Atributos permitem armazenar dados essenciais e distinguir objetos. O encapsulamento garante integridade e controle de acesso.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar modificadores de acesso (`private`).</li>
<li>Fornecer getters/setters.</li>
</ul>
<h3>Analogia:</h3>
<p>Casa = objeto, atributos = cor da parede, número de quartos, tamanho do quintal.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>System.out.println(fusca.getModelo()); // Fusca
System.out.println(gol.getModelo());   // Gol</code></pre>
CONTEUDO_02_03,
            ],
            [
                "id" => "construtores",
                "titulo" => "Construtores",
                "descricao" => "Construtores inicializam objetos, podendo receber parâmetros para definir estado inicial.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_04'
<h3>Teoria:</h3>
<p>Construtores inicializam objetos, podendo receber parâmetros para definir estado inicial.</p>
<h3>Análise e lógica:</h3>
<p>Construtores promovem consistência, garantindo que objetos comecem com valores válidos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Ter construtor padrão e parametrizado quando necessário.</li>
<li>Evitar lógica complexa dentro do construtor.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>Carro c = new Carro("Fusca", 1985);
System.out.println(c.getModelo()); // Fusca</code></pre>
CONTEUDO_02_04,
            ],
            [
                "id" => "encapsulamento",
                "titulo" => "Encapsulamento",
                "descricao" => "Encapsulamento protege dados usando modificadores de acesso e métodos de leitura/escrita (getters/setters).",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_05'
<h3>Teoria:</h3>
<p>Encapsulamento protege dados usando modificadores de acesso e métodos de leitura/escrita (getters/setters).</p>
<h3>Análise e lógica:</h3>
<p>Garante integridade e controle de acesso aos dados, evitando manipulação direta indevida.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Atributos privados, métodos públicos de acesso.</li>
<li>Validação de dados dentro de setters.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>public class Carro {
    private String modelo;

    public String getModelo() { return modelo; }
    public void setModelo(String m) { modelo = m; }
}</code></pre>
CONTEUDO_02_05,
            ],
            [
                "id" => "heranca",
                "titulo" => "Herança",
                "descricao" => "Herança permite que uma classe (subclasse) reutilize atributos e métodos de outra (superclasse).",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_06'
<h3>Teoria:</h3>
<p>Herança permite que uma classe (subclasse) reutilize atributos e métodos de outra (superclasse).</p>
<h3>Análise e lógica:</h3>
<p>Promove reusabilidade, evita duplicação e cria hierarquias de classes. Lógica: subclassificação = especialização de comportamento.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar herança profunda (&gt;3 níveis).</li>
<li>Usar herança apenas quando há relação “é-um”.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>class Veiculo {
    void ligar() { System.out.println("Ligando..."); }
}

class Carro extends Veiculo {
    void buzinar() { System.out.println("BEEP!"); }
}

Carro c = new Carro();
c.ligar();
c.buzinar();</code></pre>
CONTEUDO_02_06,
            ],
            [
                "id" => "polimorfismo",
                "titulo" => "Polimorfismo",
                "descricao" => "Polimorfismo permite que objetos de diferentes classes derivadas se comportem de forma distinta usando a mesma interface ou classe base.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_07'
<h3>Teoria:</h3>
<p>Polimorfismo permite que objetos de diferentes classes derivadas se comportem de forma distinta usando a mesma interface ou classe base.</p>
<h3>Análise e lógica:</h3>
<p>Facilita escrever código genérico e flexível, executando comportamento específico em tempo de execução.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar if/else extensos para tipos diferentes.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>class Animal { void som() { System.out.println("Som"); } }
class Cachorro extends Animal { void som() { System.out.println("Au Au"); } }
class Gato extends Animal { void som() { System.out.println("Miau"); } }

Animal a = new Cachorro();
Animal b = new Gato();
a.som(); // Au Au
b.som(); // Miau</code></pre>
CONTEUDO_02_07,
            ],
            [
                "id" => "interfaces",
                "titulo" => "Interfaces",
                "descricao" => "Interfaces definem contratos que diferentes classes podem implementar, favorecendo desacoplamento e polimorfismo.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_08'
<h3>Teoria:</h3>
<p>Interfaces definem contratos que diferentes classes podem implementar. Elas normalmente declaram comportamentos esperados sem determinar como cada classe realizará esses comportamentos.</p>
<p>Além de métodos abstratos, interfaces modernas também podem possuir métodos <strong>default</strong> e <strong>static</strong>. Métodos privados em interfaces existem a partir do Java 9.</p>
<h3>Análise e lógica:</h3>
<p>Interfaces favorecem polimorfismo e desacoplamento, permitindo que o código dependa de um contrato em vez de depender diretamente de uma implementação específica.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Use interfaces quando diferentes classes precisam cumprir o mesmo contrato.</li>
<li>Mantenha cada interface coesa e com responsabilidade clara.</li>
<li>Evite criar interfaces sem uma necessidade real de abstração.</li>
</ul>
<h3>Analogia:</h3>
<p>Interface é como um contrato: classes diferentes podem assiná-lo, mas cada uma pode cumprir suas obrigações de uma maneira própria.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>interface Animal {
    void fazerSom();
}

class Cachorro implements Animal {
    @Override
    public void fazerSom() {
        System.out.println("Au Au!");
    }
}

Animal animal = new Cachorro();
animal.fazerSom(); // Au Au!</code></pre>
CONTEUDO_02_08,
            ],
            [
                "id" => "classes-abstratas",
                "titulo" => "Classes Abstratas",
                "descricao" => "Classes abstratas não podem ser instanciadas diretamente e podem conter métodos abstratos (sem implementação) e concretos.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_09'
<h3>Teoria:</h3>
<p>Classes abstratas não podem ser instanciadas diretamente e podem conter métodos abstratos (sem implementação) e concretos.</p>
<h3>Análise e lógica:</h3>
<p>Útil para criar hierarquias com comportamento parcialmente implementado, garantindo reuso de código comum.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar quando há comportamento compartilhado entre subclasses.</li>
<li>Não abusar da abstração profunda.</li>
</ul>
<h3>Analogia:</h3>
<p>Classe abstrata = molde incompleto que precisa ser finalizado pelas subclasses.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>abstract class Animal {
    abstract void fazerSom();

    void dormir() {
        System.out.println("Dormindo...");
    }
}

class Gato extends Animal {
    void fazerSom() {
        System.out.println("Miau!");
    }
}

Animal gato = new Gato();
gato.fazerSom(); // Miau!
gato.dormir();   // Dormindo...</code></pre>
CONTEUDO_02_09,
            ],
            [
                "id" => "singleton",
                "titulo" => "Singleton",
                "descricao" => "Singleton é um padrão que controla a criação de instâncias para disponibilizar uma única instância de uma classe dentro de determinado contexto.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_10'
<h3>Teoria:</h3>
<p>Singleton é um padrão de projeto utilizado quando uma aplicação realmente precisa controlar a criação de uma única instância de determinada classe.</p>
<h3>Análise e lógica:</h3>
<p>Ele pode ser útil para configurações ou serviços compartilhados, mas também aumenta o acoplamento global. Por isso, não deve ser aplicado automaticamente a qualquer classe compartilhada.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Use Singleton somente quando existir uma necessidade real de instância única.</li>
<li>Considere testabilidade e acoplamento antes de adotar o padrão.</li>
<li>Em aplicações concorrentes, utilize uma implementação segura para múltiplas threads.</li>
</ul>
<h3>Analogia:</h3>
<p>Singleton é como uma central única de configuração consultada por diferentes partes da aplicação.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>public final class Config {

    private Config() {
    }

    private static class Holder {
        private static final Config INSTANCIA = new Config();
    }

    public static Config getInstancia() {
        return Holder.INSTANCIA;
    }
}</code></pre>
CONTEUDO_02_10,
            ],
            [
                "id" => "design-by-contract",
                "titulo" => "Design by Contract",
                "descricao" => "Design by Contract organiza regras de entrada, garantias de saída e invariantes para tornar responsabilidades do código explícitas.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_02_11'
<h3>Teoria:</h3>
<p>Design by Contract (DbC) trabalha com três ideias principais: <strong>pré-condições</strong>, <strong>pós-condições</strong> e <strong>invariantes</strong>.</p>
<p>Java não possui um mecanismo nativo completo de DbC. Na prática, contratos podem ser expressos com validações, exceções, testes, documentação e, em alguns cenários, assertions.</p>
<h3>Análise e lógica:</h3>
<p>O objetivo é deixar claro o que um método espera antes de executar e o que ele garante quando termina corretamente.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Valide pré-condições importantes na entrada dos métodos.</li>
<li>Não utilize <code>assert</code> para validar dados externos do usuário.</li>
<li>Mantenha invariantes importantes da classe sempre válidos.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>public int sacar(int saldo, int valor) {

    if (valor &lt;= 0) {
        throw new IllegalArgumentException(
            "O valor deve ser maior que zero."
        );
    }

    if (valor &gt; saldo) {
        throw new IllegalArgumentException(
            "Saldo insuficiente."
        );
    }

    int novoSaldo = saldo - valor;

    assert novoSaldo &gt;= 0;

    return novoSaldo;
}</code></pre>
CONTEUDO_02_11,
            ],
        ],
    ],
    [
        "id" => "excecoes-io",
        "titulo" => "Exceções, arquivos e registros",
        "icone" => "⚠️",
        "descricao" => "Tratamento de falhas, gerenciamento de recursos, entrada e saída, serialização e logs.",
        "nivel" => "Intermediário",
        "topicos" => [
            [
                "id" => "excecoes",
                "titulo" => "Exceções",
                "descricao" => "Exceções representam erros ou condições inesperadas que ocorrem durante a execução de um programa. Java usa classes para tratar exceções e separar código normal do código de erro.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_01'
<h3>Teoria:</h3>
<p>Exceções representam erros ou condições inesperadas que ocorrem durante a execução de um programa. Java usa classes para tratar exceções e separar código normal do código de erro.</p>
<h3>Análise e lógica:</h3>
<p>O tratamento de exceções melhora a robustez do programa, permitindo lidar com situações imprevisíveis sem interromper a execução.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Capturar exceções específicas.</li>
<li>Não usar catch(Exception) indiscriminadamente.</li>
<li>Adicionar mensagens claras ao lançar exceções.</li>
</ul>
<h3>Analogia:</h3>
<p>Exceções são como alarmes de incêndio: avisam quando algo dá errado, permitindo ações corretivas.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>try {
    int resultado = 10 / 0;
} catch (ArithmeticException e) {
    System.out.println("Erro: divisão por zero!");
} finally {
    System.out.println("Bloco finally sempre executa.");
}</code></pre>
CONTEUDO_03_01,
            ],
            [
                "id" => "try-with-resources",
                "titulo" => "Try-with-resources",
                "descricao" => "Gerencia recursos automaticamente em operações de I/O.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_02'
<h3>Teoria:</h3>
<p>Gerencia recursos automaticamente em operações de I/O.</p>
<h3>Análise e lógica:</h3>
<p>Evita leaks fechando arquivos ou streams automaticamente.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar sempre que houver recursos que precisam ser fechados.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>try (BufferedReader br = new BufferedReader(new FileReader("arquivo.txt"))) {
    String linha;
    while ((linha = br.readLine()) != null) {
        System.out.println(linha);
    }
} catch (IOException e) {
    e.printStackTrace();
}</code></pre>
CONTEUDO_03_02,
            ],
            [
                "id" => "java-i-o",
                "titulo" => "Java I/O",
                "descricao" => "Java I/O permite ler e escrever dados em arquivos, streams e outras fontes de entrada e saída.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_03'
<h3>Teoria:</h3>
<p>Java I/O reúne APIs para trabalhar com arquivos, fluxos de bytes e caracteres e outras operações de entrada e saída.</p>
<h3>Análise e lógica:</h3>
<p>Essas APIs são importantes para persistência em arquivos, leitura de dados e comunicação com diferentes fontes.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Use try-with-resources para fechar recursos automaticamente.</li>
<li>Evite carregar arquivos muito grandes inteiros na memória sem necessidade.</li>
<li>Trate IOException de maneira adequada ao contexto da aplicação.</li>
</ul>
<h3>Analogia:</h3>
<p>Streams podem ser imaginados como canais por onde os dados entram ou saem da aplicação.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;

try (
    BufferedReader reader =
        new BufferedReader(
            new FileReader("arquivo.txt")
        )
) {

    String linha;

    while ((linha = reader.readLine()) != null) {
        System.out.println(linha);
    }

} catch (IOException e) {
    System.out.println(
        "Não foi possível ler o arquivo."
    );
}</code></pre>
CONTEUDO_03_03,
            ],
            [
                "id" => "serializacao",
                "titulo" => "Serialização",
                "descricao" => "A serialização nativa transforma o estado de objetos compatíveis em uma sequência de bytes.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_04'
<h3>Teoria:</h3>
<p>A serialização nativa do Java permite transformar o estado de objetos que implementam <strong>Serializable</strong> em bytes para armazenamento ou transporte.</p>
<h3>Análise e lógica:</h3>
<p>Ela pode ser útil em cenários específicos, porém desserializar dados recebidos de fontes não confiáveis é perigoso. Em integrações modernas, formatos explícitos como JSON também são muito utilizados.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Nunca desserialize dados não confiáveis sem controles adequados.</li>
<li>Defina <code>serialVersionUID</code> quando utilizar Serializable.</li>
<li>Evite serializar informações sensíveis ou desnecessárias.</li>
<li>Considere formatos de dados mais explícitos para comunicação entre sistemas.</li>
</ul>
<h3>Analogia:</h3>
<p>Serializar é como empacotar o estado de um objeto para armazená-lo ou transportá-lo.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectOutputStream;
import java.io.Serializable;

class Pessoa implements Serializable {

    private static final long serialVersionUID = 1L;

    private final String nome;

    public Pessoa(String nome) {
        this.nome = nome;
    }
}

Pessoa pessoa = new Pessoa("João");

try (
    ObjectOutputStream saida =
        new ObjectOutputStream(
            new FileOutputStream("pessoa.ser")
        )
) {

    saida.writeObject(pessoa);

} catch (IOException e) {
    System.out.println(
        "Não foi possível salvar o objeto."
    );
}</code></pre>
CONTEUDO_03_04,
            ],
            [
                "id" => "registro-de-logs",
                "titulo" => "Registro de Logs",
                "descricao" => "Logging ajuda a monitorar execução e erros.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_05'
<h3>Teoria:</h3>
<p>Logging ajuda a monitorar execução e erros.</p>
<h3>Análise e lógica:</h3>
<p>Melhora manutenção e depuração de sistemas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar frameworks como SLF4J ou java.util.logging.</li>
<li>Evitar System.out.println em produção.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.util.logging.*;

Logger logger = Logger.getLogger("MeuLog");
logger.info("Informação de teste");</code></pre>
CONTEUDO_03_05,
            ],
            [
                "id" => "logs-avancados",
                "titulo" => "Logs avançados",
                "descricao" => "Uso de MDC, filtros, appenders customizados, integração com ELK.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_03_06'
<h3>Teoria:</h3>
<p>Uso de MDC, filtros, appenders customizados, integração com ELK.</p>
<h3>Análise e lógica:</h3>
<p>Permite rastreabilidade, monitoramento e auditoria de sistemas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar níveis de log corretos.</li>
<li>Evitar logs sensíveis em produção.</li>
</ul>
<h3>Como usar no código:</h3>
<p><strong>Observação:</strong> o exemplo abaixo utiliza SLF4J, que é uma dependência externa ao JDK.</p>
<pre class="codigo-java"><code>private static final Logger logger = LoggerFactory.getLogger(MyClass.class);
logger.info("Informação de log");
logger.error("Erro de execução", exception);</code></pre>
CONTEUDO_03_06,
            ],
        ],
    ],
    [
        "id" => "colecoes",
        "titulo" => "Coleções e estruturas de dados",
        "icone" => "📚",
        "descricao" => "Estruturas para organizar dados, segurança de tipos e coleções preparadas para concorrência.",
        "nivel" => "Intermediário",
        "topicos" => [
            [
                "id" => "colecoes",
                "titulo" => "Coleções",
                "descricao" => "Coleções armazenam grupos de objetos, permitindo fácil manipulação de dados. Incluem List, Set, Map, Queue.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_04_01'
<h3>Teoria:</h3>
<p>Coleções armazenam grupos de objetos, permitindo fácil manipulação de dados. Incluem List, Set, Map, Queue.</p>
<h3>Análise e lógica:</h3>
<p>Escolher a estrutura correta impacta performance e organização do código. List = elementos ordenados, Set = elementos únicos, Map = chave-valor.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Escolher estrutura adequada ao problema.</li>
<li>Evitar loops desnecessários; usar iteradores ou streams.</li>
</ul>
<h3>Analogia:</h3>
<p>Coleções são como caixas de armazenamento: diferentes tipos servem para diferentes necessidades.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>import java.util.*;

List&lt;String&gt; nomes = new ArrayList&lt;&gt;();
nomes.add("João");
nomes.add("Maria");

Set&lt;Integer&gt; numeros = new HashSet&lt;&gt;();
numeros.add(1);
numeros.add(2);

Map&lt;String, Integer&gt; idade = new HashMap&lt;&gt;();
idade.put("João", 30);
idade.put("Maria", 25);</code></pre>
CONTEUDO_04_01,
            ],
            [
                "id" => "generics",
                "titulo" => "Generics",
                "descricao" => "Generics permitem criar classes e métodos parametrizados, garantindo segurança de tipos e evitando casting.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_04_02'
<h3>Teoria:</h3>
<p>Generics permitem criar classes e métodos parametrizados, garantindo segurança de tipos e evitando casting.</p>
<h3>Análise e lógica:</h3>
<p>Evita erros de conversão em tempo de execução, aumentando confiabilidade e legibilidade.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar generics em coleções.</li>
<li>Evitar usar tipo cru (raw type).</li>
</ul>
<h3>Analogia:</h3>
<p>Generics são como caixas com etiquetas: você sabe exatamente o que cabe dentro.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>List&lt;String&gt; nomes = new ArrayList&lt;&gt;();
nomes.add("Ana");
String primeiro = nomes.get(0); // sem casting</code></pre>
CONTEUDO_04_02,
            ],
            [
                "id" => "colecoes-concorrentes",
                "titulo" => "Coleções Concorrentes",
                "descricao" => "ConcurrentHashMap e CopyOnWriteArrayList permitem acesso seguro por múltiplas threads.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_04_03'
<h3>Teoria:</h3>
<p>ConcurrentHashMap e CopyOnWriteArrayList permitem acesso seguro por múltiplas threads.</p>
<h3>Análise e lógica:</h3>
<p>Essas coleções previnem problemas de concorrência sem bloqueios complexos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar coleções concorrentes para múltiplas threads.</li>
<li>Evitar sincronização manual quando possível.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.util.concurrent.*;

ConcurrentHashMap&lt;String, Integer&gt; map = new ConcurrentHashMap&lt;&gt;();
map.put("a", 1);
map.put("b", 2);
System.out.println(map.get("a")); // 1</code></pre>
CONTEUDO_04_03,
            ],
        ],
    ],
    [
        "id" => "funcional",
        "titulo" => "Programação funcional e Streams",
        "icone" => "⚡",
        "descricao" => "Lambdas, interfaces funcionais, Optional e processamento declarativo de dados.",
        "nivel" => "Intermediário",
        "topicos" => [
            [
                "id" => "streams-e-lambda",
                "titulo" => "Streams e Lambda",
                "descricao" => "Lambdas são funções anônimas; streams permitem manipular coleções de forma funcional e declarativa.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_01'
<h3>Teoria:</h3>
<p>Lambdas são funções anônimas; streams permitem manipular coleções de forma funcional e declarativa.</p>
<h3>Análise e lógica:</h3>
<p>Permite filtrar, mapear e reduzir dados sem loops explícitos, tornando o código mais expressivo.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar streams para operações sobre coleções.</li>
<li>Evitar lógica complexa dentro de lambdas.</li>
</ul>
<h3>Analogia:</h3>
<p>Streams = esteira de processamento, lambdas = máquinas que processam os itens da esteira.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>List&lt;String&gt; nomes = Arrays.asList("Ana","Carlos","Beatriz");
nomes.stream()
     .filter(n -&gt; n.startsWith("A"))
     .forEach(System.out::println);</code></pre>
CONTEUDO_05_01,
            ],
            [
                "id" => "lambdas-e-interfaces-funcionais",
                "titulo" => "Lambdas e interfaces funcionais",
                "descricao" => "Lambdas permitem programação funcional. Functional Interfaces têm apenas um método abstrato.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_02'
<h3>Teoria:</h3>
<p>Lambdas permitem programação funcional. Functional Interfaces têm apenas um método abstrato.</p>
<h3>Análise e lógica:</h3>
<p>Reduz boilerplate, facilita streams e callbacks.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar expressões claras e concisas.</li>
<li>Evitar lambdas muito longas.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.util.function.Function;

Function&lt;Integer, Integer&gt; quadrado = x -&gt; x * x;
System.out.println(quadrado.apply(5)); // 25</code></pre>
CONTEUDO_05_02,
            ],
            [
                "id" => "interfaces-funcionais-personalizadas",
                "titulo" => "Interfaces funcionais personalizadas",
                "descricao" => "Criação de interfaces funcionais além das built-in, permitindo lambdas personalizadas.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_03'
<h3>Teoria:</h3>
<p>Criação de interfaces funcionais além das built-in, permitindo lambdas personalizadas.</p>
<h3>Análise e lógica:</h3>
<p>Facilita programação funcional em cenários específicos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Marcar com @FunctionalInterface.</li>
<li>Método único claro e objetivo.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>@FunctionalInterface
interface Operacao {
    int executar(int a, int b);
}

Operacao soma = (x, y) -&gt; x + y;
System.out.println(soma.executar(5,3)); // 8</code></pre>
CONTEUDO_05_03,
            ],
            [
                "id" => "optional",
                "titulo" => "Optional",
                "descricao" => "Optional representa explicitamente um valor que pode ou não estar presente e pode reduzir o uso de null em retornos de métodos.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_04'
<h3>Teoria:</h3>
<p>Optional representa explicitamente um valor que pode ou não estar presente e pode reduzir o uso de null em retornos de métodos.</p>
<h3>Análise e lógica:</h3>
<p>Reduz a necessidade de retornar null e torna a ausência de valor mais explícita no código.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar Optional ao invés de retornar null.</li>
<li>Evitar Optional em atributos de classes (usar apenas em retornos).</li>
</ul>
<h3>Analogia:</h3>
<p>Optional = envelope que pode estar vazio ou conter uma carta.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>Optional&lt;String&gt; nome = Optional.ofNullable(null);
System.out.println(nome.orElse("Não informado")); // Não informado</code></pre>
CONTEUDO_05_04,
            ],
            [
                "id" => "programacao-funcional-avancada",
                "titulo" => "Programação funcional avançada",
                "descricao" => "Optional, Predicate, Function e Consumer permitem programação funcional segura e expressiva.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_05'
<h3>Teoria:</h3>
<p>Optional, Predicate, Function e Consumer permitem programação funcional segura e expressiva.</p>
<h3>Análise e lógica:</h3>
<p>Evita nulls e torna pipelines de dados mais claros.</p>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.util.*;
import java.util.function.*;

Optional&lt;String&gt; nome = Optional.of("João");
nome.ifPresent(System.out::println);

Predicate&lt;Integer&gt; par = n -&gt; n % 2 == 0;
System.out.println(par.test(4)); // true</code></pre>
CONTEUDO_05_05,
            ],
            [
                "id" => "streams-avancados",
                "titulo" => "Streams Avançados",
                "descricao" => "Streams permitem processar coleções de forma funcional, com operações como map, filter, reduce e collect.",
                "nivel" => "Intermediário",
                "conteudo" => <<<'CONTEUDO_05_06'
<h3>Teoria:</h3>
<p>Streams permitem processar coleções de forma funcional, com operações como map, filter, reduce e collect.</p>
<h3>Análise e lógica:</h3>
<p>Facilitam processamento paralelo e transformações de dados, tornando o código mais conciso e legível.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar streams muito longos ou com efeitos colaterais.</li>
<li>Preferir expressões claras e concisas.</li>
</ul>
<h3>Analogia:</h3>
<p>Streams = esteira de fábrica onde cada estação processa os itens de forma modular.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>import java.util.*;
import java.util.stream.*;

List&lt;Integer&gt; numeros = Arrays.asList(1, 2, 3, 4, 5);
List&lt;Integer&gt; quadrados = numeros.stream()
                                  .map(n -&gt; n * n)
                                  .collect(Collectors.toList());
System.out.println(quadrados); // [1,4,9,16,25]</code></pre>
CONTEUDO_05_06,
            ],
        ],
    ],
    [
        "id" => "concorrencia",
        "titulo" => "Concorrência, assincronismo e reatividade",
        "icone" => "🧵",
        "descricao" => "Threads, memória compartilhada, tarefas assíncronas e fluxos reativos.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "threads-e-concorrencia",
                "titulo" => "Threads e Concorrência",
                "descricao" => "Threads permitem executar múltiplas tarefas simultaneamente.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_01'
<h3>Teoria:</h3>
<p>Threads permitem executar múltiplas tarefas simultaneamente.</p>
<h3>Análise e lógica:</h3>
<p>Concorrência melhora performance em sistemas com múltiplas tarefas independentes, mas exige cuidado com recursos compartilhados.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar concorrência desnecessária.</li>
<li>Sincronizar recursos compartilhados.</li>
<li>Usar ExecutorService para gerenciar threads.</li>
</ul>
<h3>Analogia:</h3>
<p>Threads = cozinheiros em uma cozinha, cada um preparando um prato ao mesmo tempo.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>Thread t = new Thread(() -&gt; System.out.println("Executando thread"));
t.start();</code></pre>
CONTEUDO_06_01,
            ],
            [
                "id" => "java-memory-model-e-concorrencia",
                "titulo" => "Java Memory Model e concorrência",
                "descricao" => "Define como threads interagem com memória. Conceitos: happens-before, volatile, atomic operations, ThreadLocal.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_02'
<h3>Teoria:</h3>
<p>Define como threads interagem com memória. Conceitos: happens-before, volatile, atomic operations, ThreadLocal.</p>
<h3>Análise e lógica:</h3>
<p>Permite entender visibilidade, consistência e sincronização entre threads.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar volatile apenas quando necessário.</li>
<li>Prefira classes do pacote java.util.concurrent.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>volatile int count = 0;

ThreadLocal&lt;String&gt; threadData = ThreadLocal.withInitial(() -&gt; "dados da thread");</code></pre>
CONTEUDO_06_02,
            ],
            [
                "id" => "programacao-assincrona-avancada",
                "titulo" => "Programação Assíncrona Avançada",
                "descricao" => "O Java oferece recursos de programação assíncrona com CompletableFuture e Reactive Streams. Permite execução paralela e composição de tarefas, essencial para sistemas escaláveis.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_03'
<h3>Teoria:</h3>
<p>O Java oferece recursos de programação assíncrona com CompletableFuture e Reactive Streams. Permite execução paralela e composição de tarefas, essencial para sistemas escaláveis.</p>
<h3>Análise e lógica:</h3>
<p>Evita bloqueios de threads, melhora throughput e responde rapidamente a múltiplas requisições simultâneas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar bloqueios em callbacks.</li>
<li>Tratar exceções de forma centralizada.</li>
<li>Usar combinadores de CompletableFuture para tarefas dependentes.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>CompletableFuture.supplyAsync(() -&gt; fetchData())
                 .thenApply(data -&gt; process(data))
                 .thenAccept(result -&gt; System.out.println(result));</code></pre>
CONTEUDO_06_03,
            ],
            [
                "id" => "concorrencia-avancada",
                "titulo" => "Concorrência avançada",
                "descricao" => "Executors, ForkJoinPool, CompletableFuture e Locks permitem paralelismo eficiente e controle preciso de threads.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_04'
<h3>Teoria:</h3>
<p>Executors, ForkJoinPool, CompletableFuture e Locks permitem paralelismo eficiente e controle preciso de threads.</p>
<h3>Análise e Lógica:</h3>
<p>Essas APIs melhoram performance em sistemas multicore, evitando condições de corrida e deadlocks.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Usar Executors e Futures em vez de criar threads manualmente.</li>
<li>Evitar bloqueios desnecessários.</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>ExecutorService executor = Executors.newFixedThreadPool(4);
executor.submit(() -&gt; System.out.println("Executando em paralelo!"));
executor.shutdown();</code></pre>
CONTEUDO_06_04,
            ],
            [
                "id" => "programacao-reativa",
                "titulo" => "Programação reativa",
                "descricao" => "Programação reativa usando Reactor ou RxJava permite fluxo de dados assíncrono e não bloqueante.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_05'
<h3>Teoria:</h3>
<p>Programação reativa usando Reactor ou RxJava permite fluxo de dados assíncrono e não bloqueante.</p>
<h3>Análise e lógica:</h3>
<p>Permite backpressure, hot/cold streams e sistemas escaláveis sem bloquear threads.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar side-effects em streams reativos.</li>
<li>Usar operators adequados para filtragem, mapeamento e combinação de fluxos.</li>
<li>Gerenciar erros centralizadamente.</li>
</ul>
<h3>Exemplo prático:</h3>
<p><strong>Observação:</strong> <code>Flux</code> pertence a bibliotecas reativas como Project Reactor e não faz parte do JDK padrão.</p>
<pre class="codigo-java"><code>Flux.range(1,5)
    .map(i -&gt; i * 2)
    .subscribe(System.out::println);</code></pre>
CONTEUDO_06_05,
            ],
            [
                "id" => "padroes-de-sistemas-reativos",
                "titulo" => "Padrões de sistemas reativos",
                "descricao" => "Programação reativa usando backpressure, hot/cold streams e fluxo assíncrono.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_06_06'
<h3>Teoria:</h3>
<p>Programação reativa usando backpressure, hot/cold streams e fluxo assíncrono.</p>
<h3>Análise e lógica:</h3>
<p>Permite criar sistemas responsivos e escaláveis.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar frameworks como Reactor ou RxJava.</li>
<li>Evitar bloqueio de threads em fluxos reativos.</li>
</ul>
<h3>Como usar no código:</h3>
<p><strong>Observação:</strong> este exemplo usa Project Reactor, uma dependência externa.</p>
<pre class="codigo-java"><code>Flux.just(1,2,3)
    .map(i -&gt; i * 2)
    .subscribe(System.out::println);</code></pre>
CONTEUDO_06_06,
            ],
        ],
    ],
    [
        "id" => "jvm-performance",
        "titulo" => "JVM, memória e performance",
        "icone" => "⚙️",
        "descricao" => "Funcionamento interno da JVM, coleta de lixo, monitoramento, profiling e otimização.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "conceitos-de-jvm",
                "titulo" => "Conceitos de JVM",
                "descricao" => "JVM é a máquina virtual responsável por executar bytecode Java, gerenciando memória, threads e segurança.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_01'
<h3>Teoria:</h3>
<p>JVM é a máquina virtual responsável por executar bytecode Java, gerenciando memória, threads e segurança.</p>
<h3>Análise e lógica:</h3>
<p>Entender JVM ajuda a otimizar performance, evitar vazamentos de memória e compreender erros de runtime.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar criar objetos desnecessários.</li>
<li>Conhecer Heap, Stack e Garbage Collector.</li>
</ul>
<h3>Analogia:</h3>
<p>JVM = motor que executa o Java e cuida da memória e segurança.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>// Observação: isso é conceitual; código depende do JVM internamente
Runtime runtime = Runtime.getRuntime();
System.out.println("Memória livre: " + runtime.freeMemory());</code></pre>
CONTEUDO_07_01,
            ],
            [
                "id" => "garbage-collector",
                "titulo" => "Garbage Collector",
                "descricao" => "O Garbage Collector identifica objetos que não estão mais alcançáveis e pode recuperar a memória ocupada por eles.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_02'
<h3>Teoria:</h3>
<p>O Garbage Collector (GC) gerencia automaticamente parte da memória utilizada pela aplicação e recupera memória de objetos que deixaram de ser alcançáveis.</p>
<h3>Análise e lógica:</h3>
<p>O GC reduz a necessidade de liberação manual de memória, mas não impede todos os vazamentos. Uma aplicação ainda pode reter referências a objetos que não precisa mais.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evite manter referências desnecessárias a objetos.</li>
<li>Meça heap, alocação e pausas antes de realizar tuning.</li>
<li>Escolha e configure coletores conforme a versão do JDK e as necessidades reais da aplicação.</li>
</ul>
<h3>Exemplo conceitual:</h3>
<pre class="codigo-java"><code>// System.gc() apenas solicita uma coleta.
// A JVM não é obrigada a executá-la imediatamente.
System.gc();

Runtime runtime = Runtime.getRuntime();

long memoriaUsada =
    runtime.totalMemory()
    - runtime.freeMemory();

System.out.println(
    "Memória usada: "
    + memoriaUsada
    + " bytes"
);</code></pre>
<p><strong>Compatibilidade:</strong> a opção <code>-Xlog:gc*</code> pertence ao sistema de logs unificados introduzido após o Java 8.</p>
<pre class="codigo-java"><code>java -Xlog:gc* MyApp</code></pre>
CONTEUDO_07_02,
            ],
            [
                "id" => "gerenciamento-avancado-de-memoria",
                "titulo" => "Gerenciamento avançado de memória",
                "descricao" => "Referências Soft, Weak e Phantom oferecem semânticas especiais de alcançabilidade e interação com o Garbage Collector.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_03'
<h3>Teoria:</h3>
<p>Além das referências comuns, Java possui referências <strong>Soft</strong>, <strong>Weak</strong> e <strong>Phantom</strong>, cada uma com regras específicas de alcançabilidade e coleta.</p>
<h3>Análise e lógica:</h3>
<p>Esses recursos são avançados e devem ser utilizados somente quando sua semântica for realmente necessária. Eles não substituem uma estratégia clara de gerenciamento de recursos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evite utilizar referências especiais sem compreender o comportamento do GC.</li>
<li>Não dependa de SoftReference como solução genérica de cache.</li>
<li>Utilize ferramentas de profiling para investigar retenção de objetos.</li>
</ul>
<h3>Exemplo conceitual:</h3>
<pre class="codigo-java"><code>import java.lang.ref.SoftReference;

Object objeto = new Object();

SoftReference&lt;Object&gt; referencia =
    new SoftReference&lt;&gt;(objeto);

objeto = null;

Object valor =
    referencia.get();

if (valor != null) {
    System.out.println(
        "O objeto ainda está disponível."
    );
}</code></pre>
CONTEUDO_07_03,
            ],
            [
                "id" => "monitoramento-e-performance",
                "titulo" => "Monitoramento e performance",
                "descricao" => "Ferramentas como JMX e profilers permitem monitorar JVM, uso de memória e threads em execução.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_04'
<h3>Teoria:</h3>
<p>Ferramentas como JMX e profilers permitem monitorar JVM, uso de memória e threads em execução.</p>
<h3>Análise e lógica:</h3>
<p>Identificar gargalos e leaks é fundamental para manter sistemas performáticos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar VisualVM para análise de heap e threads.</li>
<li>Evitar alocação excessiva em loops críticos.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>// Monitoramento via JMX ou VisualVM, código Java não necessário</code></pre>
CONTEUDO_07_04,
            ],
            [
                "id" => "profiling-e-otimizacao-de-performance",
                "titulo" => "Profiling e otimização de performance",
                "descricao" => "Ferramentas de profiling analisam uso de CPU, memória, JIT compilation, escape analysis e vazamentos.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_05'
<h3>Teoria:</h3>
<p>Ferramentas de profiling analisam uso de CPU, memória, JIT compilation, escape analysis e vazamentos.</p>
<h3>Análise e Lógica:</h3>
<p>Permite identificar gargalos e otimizar código crítico.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Medir antes de otimizar.</li>
<li>Focar em hotspots do aplicativo.</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>// Exemplo conceitual de profiling com VisualVM
System.out.println("Rodar com VisualVM e observar heap, threads e CPU");</code></pre>
CONTEUDO_07_05,
            ],
            [
                "id" => "profiling-avancado-de-gc",
                "titulo" => "Profiling Avançado de GC",
                "descricao" => "Tipos de coletor: G1, ZGC, tuning do Garbage Collector.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_06'
<h3>Teoria:</h3>
<p>Tipos de coletor: G1, ZGC, tuning do Garbage Collector.</p>
<h3>Análise e lógica:</h3>
<p>Ajuda a otimizar memória, reduzir pausas e aumentar performance.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Monitorar heap e pausas de GC.</li>
<li>Escolher coletor adequado ao tipo de aplicação.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>java -Xlog:gc* MyApp</code></pre>
<p>Usar logs para analisar pausas, heap e frequência de coleta.</p>
CONTEUDO_07_06,
            ],
            [
                "id" => "ajustes-de-memoria-e-internals-da-jvm",
                "titulo" => "Ajustes de memória e internals da JVM",
                "descricao" => "Explora tuning da JVM: JIT optimizations, HotSpot, Metaspace, Thread stacks e memory leaks.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_07'
<h3>Teoria:</h3>
<p>Explora tuning da JVM: JIT optimizations, HotSpot, Metaspace, Thread stacks e memory leaks.</p>
<h3>Análise e lógica:</h3>
<p>Entender internals da JVM ajuda a otimizar performance e detectar problemas de memória antes que impactem o sistema.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Monitorar uso de memória com VisualVM ou Mission Control.</li>
<li>Evitar objetos desnecessários que aumentam GC pressure.</li>
<li>Usar profiling para hotspots críticos.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>// Não há código direto, mas ferramentas como jcmd, jconsole e VisualVM permitem tuning e profiling</code></pre>
CONTEUDO_07_07,
            ],
            [
                "id" => "testes-de-performance-e-stress",
                "titulo" => "Testes de Performance e Stress",
                "descricao" => "JMH, Gatling, JMeter para medir desempenho e comportamento sob carga.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_07_08'
<h3>Teoria:</h3>
<p>JMH, Gatling, JMeter para medir desempenho e comportamento sob carga.</p>
<h3>Análise e lógica:</h3>
<p>Importante para identificar gargalos e otimizar sistemas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Simular cenários reais de uso.</li>
<li>Automatizar benchmarks e testes de carga.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>@Benchmark
public void testMethod() {
    // código para medir performance
}</code></pre>
CONTEUDO_07_08,
            ],
        ],
    ],
    [
        "id" => "plataforma",
        "titulo" => "Plataforma Java e recursos avançados",
        "icone" => "🛠️",
        "descricao" => "Pacotes, módulos, carregamento de classes, reflexão, anotações, GUI, redes e integração nativa.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "anotacoes",
                "titulo" => "Anotações",
                "descricao" => "Annotations são metadados que informam o compilador ou frameworks sobre o comportamento de classes, métodos ou atributos.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_01'
<h3>Teoria:</h3>
<p>Annotations são metadados que informam o compilador ou frameworks sobre o comportamento de classes, métodos ou atributos.</p>
<h3>Análise e lógica:</h3>
<p>Facilitam configuração, validação e documentação automatizada.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar anotações padrão sempre que possível.</li>
<li>Evitar criar anotações customizadas sem necessidade.</li>
</ul>
<h3>Analogia:</h3>
<p>Annotation = etiqueta que dá instruções extras sobre o objeto.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>@Override
public String toString() {
    return "Exemplo de annotation";
}

@Deprecated
public void metodoAntigo() {}</code></pre>
CONTEUDO_08_01,
            ],
            [
                "id" => "anotacoes-avancadas",
                "titulo" => "Anotações avançadas",
                "descricao" => "Permitem criar metadados customizados e processar em runtime.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_02'
<h3>Teoria:</h3>
<p>Permitem criar metadados customizados e processar em runtime.</p>
<h3>Análise e lógica:</h3>
<p>Facilitam frameworks e validações automáticas.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Documentar claramente as annotations.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import java.lang.annotation.*;

@Retention(RetentionPolicy.RUNTIME)
@interface Info {
    String autor();
    String versao();
}

@Info(autor="João", versao="1.0")
class MeuServico {}</code></pre>
CONTEUDO_08_02,
            ],
            [
                "id" => "processadores-de-anotacoes",
                "titulo" => "Processadores de anotações",
                "descricao" => "Permite processar anotações em tempo de compilação para gerar código ou validações.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_03'
<h3>Teoria:</h3>
<p>Permite processar anotações em tempo de compilação para gerar código ou validações.</p>
<h3>Análise e lógica:</h3>
<p>Automatiza tarefas repetitivas e garante consistência.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar para geração de código seguro.</li>
<li>Evitar lógica complexa em processors.</li>
</ul>
<h3>Compatibilidade:</h3>
<p>O exemplo abaixo declara <code>SourceVersion.RELEASE_11</code>, portanto está direcionado ao Java 11.</p>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>@SupportedAnnotationTypes("MyAnnotation")
@SupportedSourceVersion(SourceVersion.RELEASE_11)
public class MyProcessor extends AbstractProcessor {
    @Override
    public boolean process(Set&lt;TypeElement&gt; annotations, RoundEnvironment roundEnv) {
        for (Element e : roundEnv.getElementsAnnotatedWith(MyAnnotation.class)) {
            // gerar código ou validações
        }
        return true;
    }
}</code></pre>
CONTEUDO_08_03,
            ],
            [
                "id" => "reflexao",
                "titulo" => "Reflexão",
                "descricao" => "Permite inspecionar e manipular classes em tempo de execução.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_04'
<h3>Teoria:</h3>
<p>Permite inspecionar e manipular classes em tempo de execução.</p>
<h3>Análise e lógica:</h3>
<p>Útil para frameworks, mas deve ser usado com cuidado.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar em produção sem necessidade.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>Class&lt;String&gt; clazz = String.class;
System.out.println(clazz.getName()); // java.lang.String</code></pre>
CONTEUDO_08_04,
            ],
            [
                "id" => "modulos-java-9",
                "titulo" => "Módulos (Java 9+)",
                "descricao" => "Permite modularizar aplicações usando module-info.java.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_05'
<h3>Teoria:</h3>
<p>Permite modularizar aplicações usando module-info.java.</p>
<h3>Análise e lógica:</h3>
<p>Melhora encapsulamento e controle de dependências.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Organizar pacotes e dependências em módulos claros.</li>
</ul>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>// module-info.java
module meu.modulo {
    requires java.sql;
    exports com.minha.app;
}</code></pre>
CONTEUDO_08_05,
            ],
            [
                "id" => "custom-classloaders",
                "titulo" => "Custom ClassLoaders",
                "descricao" => "Permite carregar classes dinamicamente em tempo de execução.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_06'
<h3>Teoria:</h3>
<p>Permite carregar classes dinamicamente em tempo de execução.</p>
<h3>Análise e lógica:</h3>
<p>Essencial para frameworks, plugins e hot-reload.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar conflitos de classes.</li>
<li>Fechar recursos ao descarregar classes.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>public class MyClassLoader extends ClassLoader {
    public Class&lt;?&gt; loadClassFromBytes(String name, byte[] bytes) {
        return defineClass(name, bytes, 0, bytes.length);
    }
}</code></pre>
CONTEUDO_08_06,
            ],
            [
                "id" => "pacotes",
                "titulo" => "Pacotes",
                "descricao" => "Pacotes organizam classes em namespaces, facilitando manutenção e evitando conflitos de nomes.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_07'
<h3>Teoria:</h3>
<p>Pacotes organizam classes em namespaces, facilitando manutenção e evitando conflitos de nomes.</p>
<h3>Análise e lógica:</h3>
<p>Permite modularizar o código, criar bibliotecas reutilizáveis e separar responsabilidades.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Criar pacotes claros e hierárquicos.</li>
<li>Evitar pacotes muito grandes ou misturar conceitos diferentes.</li>
</ul>
<h3>Analogia:</h3>
<p>Pacote é como uma pasta em um computador: organiza arquivos para fácil acesso.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>package br.com.javaland.utils;

public class Calculadora {
    public static int soma(int a, int b) {
        return a + b;
    }
}</code></pre>
CONTEUDO_08_07,
            ],
            [
                "id" => "javafx-e-interfaces-graficas",
                "titulo" => "JavaFX e interfaces gráficas",
                "descricao" => "JavaFX é uma plataforma para criação de interfaces gráficas em aplicações Java desktop.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_08'
<h3>Teoria:</h3>
<p>JavaFX permite desenvolver aplicações desktop com janelas, controles, eventos, cenas e componentes visuais.</p>
<h3>Análise e lógica:</h3>
<p>A interface pode ser organizada separadamente da lógica da aplicação, facilitando manutenção e evolução do projeto.</p>
<h3>Compatibilidade:</h3>
<p>JavaFX fazia parte do JDK 8. Em versões modernas do Java, normalmente é utilizado separadamente por meio do projeto OpenJFX e de dependências próprias.</p>
<h3>Exemplo:</h3>
<pre class="codigo-java"><code>import javafx.application.Application;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.stage.Stage;

public class Main extends Application {

    @Override
    public void start(Stage stage) {

        Button botao =
            new Button("Clique!");

        Scene cena =
            new Scene(
                botao,
                200,
                100
            );

        stage.setScene(cena);
        stage.show();
    }
}</code></pre>
CONTEUDO_08_08,
            ],
            [
                "id" => "java-native-interface-jni",
                "titulo" => "Java Native Interface (JNI)",
                "descricao" => "JNI permite que programas Java chamem código nativo (C/C++) e vice-versa, criando interoperabilidade entre linguagens.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_09'
<h3>Teoria:</h3>
<p>JNI permite que programas Java chamem código nativo (C/C++) e vice-versa, criando interoperabilidade entre linguagens.</p>
<h3>Análise e Lógica:</h3>
<p>Útil para usar bibliotecas nativas, otimizar performance ou acessar APIs do sistema operacional que não têm suporte direto em Java.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Usar JNI apenas quando necessário.</li>
<li>Evitar complexidade desnecessária e vazamentos de memória.</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>// Exemplo conceitual:
public class HelloJNI {
    static { System.loadLibrary("hello"); }
    private native void sayHello();
}</code></pre>
CONTEUDO_08_09,
            ],
            [
                "id" => "internacionalizacao-i18n-l10n",
                "titulo" => "Internacionalização (i18n / l10n)",
                "descricao" => "Java fornece APIs para suportar múltiplos idiomas e formatos regionais usando ResourceBundle e Locale.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_10'
<h3>Teoria:</h3>
<p>Java fornece APIs para suportar múltiplos idiomas e formatos regionais usando ResourceBundle e Locale.</p>
<h3>Análise e Lógica:</h3>
<p>Permite criar aplicações globais sem alterar a lógica de negócio.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Separar strings de UI em arquivos de recursos.</li>
<li>Testar formatos regionais (datas, moedas).</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>Locale brasil = Locale.forLanguageTag("pt-BR");
ResourceBundle rb = ResourceBundle.getBundle("Messages", brasil);
System.out.println(rb.getString("welcome"));</code></pre>
CONTEUDO_08_10,
            ],
            [
                "id" => "redes-avancadas-em-java",
                "titulo" => "Redes avançadas em Java",
                "descricao" => "Inclui Sockets TCP/UDP, WebSockets e HttpClient (Java 11+) para comunicação de rede.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_08_11'
<h3>Teoria:</h3>
<p>Inclui Sockets TCP/UDP, WebSockets e HttpClient (Java 11+) para comunicação de rede.</p>
<h3>Análise e lógica:</h3>
<p>Esses recursos permitem criar aplicações distribuídas, servidores, clientes e APIs REST.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Fechar streams e sockets corretamente.</li>
<li>Tratar exceções de I/O e timeouts.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>import java.net.*;
import java.io.*;

Socket socket = new Socket("localhost", 8080);
PrintWriter out = new PrintWriter(socket.getOutputStream(), true);
out.println("Olá, servidor!");
socket.close();</code></pre>
CONTEUDO_08_11,
            ],
        ],
    ],
    [
        "id" => "dados-web",
        "titulo" => "Banco de dados e desenvolvimento web",
        "icone" => "🌐",
        "descricao" => "Persistência, APIs, aplicações web, microserviços e comunicação entre sistemas.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "banco-de-dados-com-jdbc-jpa-e-hibernate",
                "titulo" => "Banco de dados com JDBC, JPA e Hibernate",
                "descricao" => "Java pode acessar bancos relacionais diretamente com JDBC ou por abstrações ORM como JPA e Hibernate.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_09_01'
<h3>Teoria:</h3>
<p>JDBC é a API padrão para comunicação direta com bancos relacionais. JPA define uma especificação de persistência orientada a objetos, e Hibernate é uma implementação amplamente utilizada dessa especificação.</p>
<h3>Análise e lógica:</h3>
<p>JDBC oferece maior proximidade com SQL. ORM pode reduzir código repetitivo de persistência, mas exige conhecimento sobre transações, consultas e comportamento do mapeamento.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Utilize PreparedStatement para parâmetros fornecidos dinamicamente.</li>
<li>Feche Connection, Statement e ResultSet com try-with-resources.</li>
<li>Utilize transações quando várias operações precisarem ser atômicas.</li>
<li>Não monte SQL concatenando diretamente dados do usuário.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>String sql =
    "SELECT nome "
    + "FROM usuarios "
    + "WHERE ativo = ?";

try (
    Connection conexao =
        DriverManager.getConnection(
            url,
            usuario,
            senha
        );

    PreparedStatement comando =
        conexao.prepareStatement(sql)
) {

    comando.setBoolean(
        1,
        true
    );

    try (
        ResultSet resultado =
            comando.executeQuery()
    ) {

        while (resultado.next()) {
            System.out.println(
                resultado.getString("nome")
            );
        }
    }
}</code></pre>
CONTEUDO_09_01,
            ],
            [
                "id" => "desenvolvimento-web-com-servlets-jsp-e-spring-boot",
                "titulo" => "Desenvolvimento web com Servlets, JSP e Spring Boot",
                "descricao" => "Java é muito usada para desenvolvimento web, incluindo APIs REST e aplicações completas via Servlets, JSP ou Spring Boot.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_09_02'
<h3>Teoria:</h3>
<p>Java é muito usada para desenvolvimento web, incluindo APIs REST e aplicações completas via Servlets, JSP ou Spring Boot.</p>
<h3>Análise e lógica:</h3>
<p>Spring Boot simplifica a criação de APIs REST e gestão de dependências, aumentando produtividade e organização.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Seguir arquitetura MVC.</li>
<li>Tratar exceções de forma centralizada.</li>
<li>Testar endpoints com unit/integration tests.</li>
</ul>
<h3>Exemplo prático:</h3>
<p><strong>Observação:</strong> o exemplo utiliza Spring Boot/Spring Web, que são dependências externas ao JDK.</p>
<pre class="codigo-java"><code>@RestController
@RequestMapping("/api")
public class UsuarioController {
    @GetMapping("/usuarios")
    public List&lt;Usuario&gt; listar() {
        return usuarioService.listarTodos();
    }
}</code></pre>
CONTEUDO_09_02,
            ],
            [
                "id" => "microservices-em-java",
                "titulo" => "Microservices em Java",
                "descricao" => "Arquitetura orientada a serviços: dividir sistemas em serviços independentes, escaláveis e de fácil manutenção.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_09_03'
<h3>Teoria:</h3>
<p>Arquitetura orientada a serviços: dividir sistemas em serviços independentes, escaláveis e de fácil manutenção.</p>
<h3>Análise e Lógica:</h3>
<p>Spring Cloud, REST, gRPC e Kafka permitem comunicação confiável entre microservices.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Manter serviços desacoplados.</li>
<li>Documentar APIs com Swagger/OpenAPI.</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>@RestController
public class UserController {
    @GetMapping("/users")
    public List&lt;User&gt; getAll() { return userService.findAll(); }
}</code></pre>
CONTEUDO_09_03,
            ],
            [
                "id" => "integracao-com-servicos-externos",
                "titulo" => "Integração com Serviços Externos",
                "descricao" => "Kafka, RabbitMQ e gRPC permitem comunicação entre microservices. REST e GraphQL para APIs.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_09_04'
<h3>Teoria:</h3>
<p>Kafka, RabbitMQ e gRPC permitem comunicação entre microservices. REST e GraphQL para APIs.</p>
<h3>Análise e lógica:</h3>
<p>As integrações desacoplam serviços e permitem processamento assíncrono e escalável.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar idempotência em endpoints.</li>
<li>Tratar falhas com circuit breaker.</li>
<li>Evitar tight coupling entre serviços.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>KafkaTemplate&lt;String, String&gt; kafkaTemplate;
kafkaTemplate.send("topic", "mensagem");</code></pre>
CONTEUDO_09_04,
            ],
        ],
    ],
    [
        "id" => "arquitetura-ferramentas",
        "titulo" => "Arquitetura, testes e ferramentas",
        "icone" => "🏛️",
        "descricao" => "Padrões, organização de sistemas, testes, builds, configuração e observabilidade.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "design-patterns",
                "titulo" => "Design Patterns",
                "descricao" => "Padrões de projeto são soluções comprovadas para problemas recorrentes no desenvolvimento de software.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_01'
<h3>Teoria:</h3>
<p>Padrões de projeto são soluções comprovadas para problemas recorrentes no desenvolvimento de software.</p>
<h3>Análise e lógica:</h3>
<p>Melhoram a manutenção, legibilidade e reuso do código.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Aplicar padrões somente quando necessário.</li>
<li>Conhecer o problema antes de escolher o padrão.</li>
</ul>
<h3>Analogia:</h3>
<p>Padrão de projeto = receita pronta que resolve um problema específico.</p>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>// Singleton
class Config {
    private static Config instance;
    private Config() {}
    public static Config getInstance() {
        if(instance == null) instance = new Config();
        return instance;
    }
}</code></pre>
CONTEUDO_10_01,
            ],
            [
                "id" => "testes-unitarios-junit",
                "titulo" => "Testes Unitários (JUnit)",
                "descricao" => "JUnit permite testar métodos individualmente.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_02'
<h3>Teoria:</h3>
<p>JUnit permite testar métodos individualmente.</p>
<h3>Análise e lógica:</h3>
<p>Garantir que pequenas partes do código funcionem isoladamente.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Testar métodos públicos e casos de borda.</li>
</ul>
<h3>Exemplo:</h3>
<p><strong>Observação:</strong> JUnit é uma biblioteca de testes e precisa ser adicionada como dependência do projeto.</p>
<pre class="codigo-java"><code>import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.Test;

class CalcTest {
    @Test
    void somaTeste() {
        assertEquals(4, 2+2);
    }
}</code></pre>
CONTEUDO_10_02,
            ],
            [
                "id" => "ferramentas-de-build-e-ci-cd",
                "titulo" => "Ferramentas de build e CI/CD",
                "descricao" => "Maven e Gradle automatizam build, testes, dependências e deploy de aplicações Java.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_03'
<h3>Teoria:</h3>
<p>Maven e Gradle automatizam build, testes, dependências e deploy de aplicações Java.</p>
<h3>Análise e Lógica:</h3>
<p>CI/CD garante integração contínua, testes automáticos e deploy seguro.</p>
<h3>Boas Práticas:</h3>
<ul>
<li>Configurar pipelines para testes automáticos.</li>
<li>Versionar dependências explicitamente.</li>
</ul>
<h3>Exemplo Prático:</h3>
<pre class="codigo-java"><code>// Exemplo de pom.xml ou build.gradle para dependências
mvn clean install
gradle build</code></pre>
CONTEUDO_10_03,
            ],
            [
                "id" => "arquitetura-e-boas-praticas",
                "titulo" => "Arquitetura e Boas Práticas",
                "descricao" => "Layered Architecture, Clean Architecture, DDD (Domain-Driven Design).",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_04'
<h3>Teoria:</h3>
<p>Layered Architecture, Clean Architecture, DDD (Domain-Driven Design).</p>
<h3>Análise e lógica:</h3>
<p>Separação de responsabilidades e manutenção facilitada.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Evitar classes God Objects.</li>
<li>Manter separação de camadas.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>// Controller
@RestController
public class UserController { ... }

// Service
@Service
public class UserService { ... }

// Repository
@Repository
public interface UserRepository extends JpaRepository&lt;User, Long&gt; { }</code></pre>
CONTEUDO_10_04,
            ],
            [
                "id" => "padroes-arquiteturais-avancados",
                "titulo" => "Padrões Arquiteturais Avançados",
                "descricao" => "Padrões como Saga, Circuit Breaker e Retry ajudam a criar sistemas distribuídos resilientes.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_05'
<h3>Teoria:</h3>
<p>Padrões como Saga, Circuit Breaker e Retry ajudam a criar sistemas distribuídos resilientes.</p>
<h3>Análise e lógica:</h3>
<p>Permitem tolerância a falhas e escalabilidade sem impactar a integridade dos dados.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Implementar retries com backoff exponencial.</li>
<li>Evitar acoplamento entre serviços.</li>
<li>Monitorar falhas e latência.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>@RestController
public class PaymentController {
    @PostMapping("/pay")
    @Retryable(maxAttempts = 3, backoff = @Backoff(delay = 2000))
    public void processPayment() { ... }
}</code></pre>
CONTEUDO_10_05,
            ],
            [
                "id" => "feature-flags-e-configuracao-dinamica",
                "titulo" => "Feature Flags e Configuração Dinâmica",
                "descricao" => "Permite habilitar ou desabilitar funcionalidades em tempo de execução.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_06'
<h3>Teoria:</h3>
<p>Permite habilitar ou desabilitar funcionalidades em tempo de execução.</p>
<h3>Análise e lógica:</h3>
<p>Útil para deploys progressivos e controle de funcionalidades.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Usar Spring Cloud Config ou LaunchDarkly.</li>
<li>Evitar lógica de flags espalhada no código.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>@Value("${feature.new-ui:false}")
private boolean newUIEnabled;

if(newUIEnabled) {
    showNewUI();
} else {
    showOldUI();
}</code></pre>
CONTEUDO_10_06,
            ],
            [
                "id" => "event-sourcing-e-cqrs",
                "titulo" => "Event Sourcing e CQRS",
                "descricao" => "Padrões para registrar eventos e separar leitura/escrita de dados.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_07'
<h3>Teoria:</h3>
<p>Padrões para registrar eventos e separar leitura/escrita de dados.</p>
<h3>Análise e lógica:</h3>
<p>Melhora auditabilidade e escalabilidade de sistemas distribuídos.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Definir claramente eventos de domínio.</li>
<li>Evitar acoplamento excessivo entre comandos e consultas.</li>
</ul>
<h3>Como usar no código:</h3>
<pre class="codigo-java"><code>// Comando
public class CreateOrderCommand { ... }

// Evento
public class OrderCreatedEvent { ... }

// Handler
public class OrderService {
    public void handle(CreateOrderCommand cmd) {
        // gerar evento
        publish(new OrderCreatedEvent(...));
    }
}</code></pre>
CONTEUDO_10_07,
            ],
            [
                "id" => "observabilidade-e-monitoramento",
                "titulo" => "Observabilidade e monitoramento",
                "descricao" => "OpenTelemetry e JMX permitem monitorar métricas, logs e tracing distribuído de aplicações Java.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_10_08'
<h3>Teoria:</h3>
<p>OpenTelemetry e JMX permitem monitorar métricas, logs e tracing distribuído de aplicações Java.</p>
<h3>Análise e lógica:</h3>
<p>Observabilidade ajuda a detectar gargalos, monitorar threads, GC e consumo de memória.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Centralizar logs e usar MDC.</li>
<li>Monitorar métricas críticas de JVM e serviços.</li>
<li>Alertas para thresholds críticos.</li>
</ul>
<h3>Exemplo prático:</h3>
<pre class="codigo-java"><code>MeterRegistry registry = new SimpleMeterRegistry();
Counter counter = registry.counter("requests_total");
counter.increment();</code></pre>
CONTEUDO_10_08,
            ],
        ],
    ],
    [
        "id" => "seguranca",
        "titulo" => "Segurança",
        "icone" => "🔒",
        "descricao" => "Proteção de dados, segurança web e controle de permissões em aplicações Java.",
        "nivel" => "Avançado",
        "topicos" => [
            [
                "id" => "seguranca-em-java",
                "titulo" => "Segurança em Java",
                "descricao" => "Java oferece APIs para criptografia, derivação de chaves, hashing, geração segura de números aleatórios e comunicação protegida.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_11_01'
<h3>Teoria:</h3>
<p>Java possui APIs de segurança e criptografia para diferentes finalidades, como integridade de dados, criptografia, certificados, TLS e derivação de chaves.</p>
<h3>Análise e lógica:</h3>
<p>O algoritmo precisa ser escolhido de acordo com o problema. Uma função de hash rápida como SHA-256 pode ser adequada para integridade, mas <strong>não deve ser utilizada sozinha para armazenar senhas</strong>.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Nunca armazene senhas em texto puro.</li>
<li>Para senhas, utilize funções próprias de derivação ou hashing de senha, com salt e fator de custo apropriado.</li>
<li>Não implemente algoritmos criptográficos próprios.</li>
<li>Proteja chaves e segredos fora do código-fonte.</li>
<li>Utilize TLS para proteger dados em trânsito.</li>
</ul>
<h3>Exemplo didático com PBKDF2:</h3>
<pre class="codigo-java"><code>import java.security.SecureRandom;
import java.util.Base64;

import javax.crypto.SecretKeyFactory;
import javax.crypto.spec.PBEKeySpec;

char[] senha =
    "senha123".toCharArray();

byte[] salt =
    new byte[16];

new SecureRandom()
    .nextBytes(salt);

PBEKeySpec especificacao =
    new PBEKeySpec(
        senha,
        salt,
        600_000,
        256
    );

SecretKeyFactory fabrica =
    SecretKeyFactory.getInstance(
        "PBKDF2WithHmacSHA256"
    );

byte[] hash =
    fabrica
        .generateSecret(especificacao)
        .getEncoded();

System.out.println(
    Base64.getEncoder()
        .encodeToString(hash)
);

especificacao.clearPassword();</code></pre>
<p><strong>Importante:</strong> em um sistema real, o salt, os parâmetros usados e o hash precisam ser armazenados de forma adequada. Parâmetros de segurança devem ser revisados conforme as recomendações atuais.</p>
CONTEUDO_11_01,
            ],
            [
                "id" => "seguranca-web",
                "titulo" => "Segurança web",
                "descricao" => "Autenticação, autorização, OAuth2, JWT, CSRF, CORS e prevenção de vulnerabilidades comuns em aplicações web.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_11_02'
<h3>Teoria:</h3>
<p>Aplicações web precisam tratar autenticação, autorização, proteção contra CSRF, XSS, injeções, configuração de CORS e transporte seguro.</p>
<h3>Análise e lógica:</h3>
<p>Segurança deve ser aplicada em camadas. Frameworks ajudam a implementar controles, mas não substituem uma configuração correta nem validação adequada dos dados.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Valide entradas de acordo com o formato esperado.</li>
<li>Use consultas parametrizadas para acesso a banco de dados.</li>
<li>Escape dados conforme o contexto de saída para reduzir risco de XSS.</li>
<li>Mantenha CSRF habilitado quando o modelo de autenticação exigir essa proteção.</li>
<li>Utilize bibliotecas e frameworks mantidos e atualizados.</li>
</ul>
<h3>Exemplo com Spring Security moderno:</h3>
<p><strong>Observação:</strong> Spring Security é uma dependência externa. O exemplo abaixo utiliza a abordagem com <code>SecurityFilterChain</code>, que substituiu o antigo <code>WebSecurityConfigurerAdapter</code>.</p>
<pre class="codigo-java"><code>@Configuration
@EnableWebSecurity
public class SecurityConfig {

    @Bean
    SecurityFilterChain securityFilterChain(
        HttpSecurity http
    ) throws Exception {

        http
            .authorizeHttpRequests(
                autorizacao -&gt;
                    autorizacao
                        .requestMatchers(
                            "/public/**"
                        )
                        .permitAll()
                        .anyRequest()
                        .authenticated()
            )
            .oauth2Login(
                Customizer.withDefaults()
            );

        return http.build();
    }
}</code></pre>
CONTEUDO_11_02,
            ],
            [
                "id" => "security-manager-e-permissoes",
                "titulo" => "Security Manager (legado)",
                "descricao" => "O Security Manager é uma API histórica do Java, depreciada para remoção e permanentemente desabilitada a partir do JDK 24.",
                "nivel" => "Avançado",
                "conteudo" => <<<'CONTEUDO_11_03'
<h3>Teoria:</h3>
<p>O <strong>Security Manager</strong> foi criado para aplicar políticas de permissão dentro da JVM. Ele fazia parte de modelos antigos de sandboxing e controle de acesso.</p>
<h3>Situação atual:</h3>
<p>O Security Manager foi depreciado para remoção no Java 17 e está <strong>permanentemente desabilitado a partir do JDK 24</strong>. Não deve ser utilizado em novos projetos.</p>
<h3>Análise e lógica:</h3>
<p>Segurança moderna deve ser construída com isolamento de processos, permissões do sistema operacional, contêineres, autenticação, autorização, validação, dependências atualizadas e outros controles apropriados ao ambiente.</p>
<h3>Boas práticas:</h3>
<ul>
<li>Não baseie a segurança de aplicações novas no Security Manager.</li>
<li>Ao manter sistemas antigos, planeje a migração para controles de segurança modernos.</li>
<li>Aplique o princípio do menor privilégio no sistema operacional e na infraestrutura.</li>
</ul>
<h3>Exemplo conceitual:</h3>
<pre class="codigo-java"><code>// Código legado:
//
// System.setSecurityManager(
//     new SecurityManager()
// );
//
// Não utilize esta abordagem em projetos novos.
// No JDK 24, a instalação de um Security Manager
// não é suportada.</code></pre>
CONTEUDO_11_03,
            ],
        ],
    ],
];
