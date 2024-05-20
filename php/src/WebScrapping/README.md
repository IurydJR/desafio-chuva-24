
### Título do Projeto:

Desafio Chuva PHP - WebScrapping

### Descrição:

Este projeto consiste em um aplicativo para realizar web scraping em uma página HTML específica, extrair informações sobre artigos acadêmicos e gerar um arquivo XLSX com os dados extraídos. O projeto faz parte do desafio proposto pela chuva inc.

### Tecnologias Utilizadas:

-   PHP (DOM)
-   Biblioteca Spout para PHP

### Estrutura do Projeto:

 

    assets/
      - origin.html (arquivo HTML de origem para web scraping)
      - paper.xlsx
    - Entity/
      - Paper.php (classe que representa um artigo acadêmico)
      - Person.php (classe que representa uma pessoa)
    - Scrapper.php (classe responsável por realizar o web scraping)
    - Spouter.php (classe responsável por gerar o arquivo XLSX)
    - Main.php (classe principal para execução do projeto)

 

### Funcionalidades:

1.  WebScrapping:
    
    -   A classe `Scrapper` é responsável por realizar o web scraping da página HTML de origem.
    -   Extrai informações sobre artigos acadêmicos, incluindo título, autores, instituições e tipo de apresentação.
    -   Retorna os dados extraídos em formato de array.
2.  Geração de Arquivo XLSX:
    
    -   A classe `Spouter` é responsável por gerar um arquivo XLSX com os dados extraídos.
    -   Utilize a biblioteca Spout para PHP para criar e escrever os dados no arquivo XLSX.
    -   Formato como células do arquivo XLSX com estilos específicos.
3.  Execução do Projeto:
    
    -   A classe `Main` é responsável por orquestrar a execução do projeto.
    -   Carregue a página HTML de origem.
    -   Utilize uma classe `Scrapper` para realizar o web scraping e extrair os dados.
    -   Utilize a classe `Spouter` para gerar o arquivo XLSX com os dados extraídos.

### Uso:

1.  Certifique-se de ter PHP instalado em seu ambiente de desenvolvimento.
2.  Instale a biblioteca Spout para PHP utilizando o Composer.
3.  Execute o arquivo `Main.php` para iniciar o projeto.

### Exemplo de uso:

`// Executar o projeto
php Main.php` 

### Autores:

Iury de Jesus Rodrigues
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/iury-djr/) [![Github](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/IurydJR)
