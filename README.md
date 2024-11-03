# Conversor de Moedas em PHP utilizando a APi awesome

- Esse projeto é um conversor de moedas em php, fazendo a integração com a api `Awesome API`, onde busca as moedas e as informações necessárias como a taxa de cotação para que a conversão das moedas sejam realizadas.

- O projeto utiliza a **Programação funcional**, onde mantém todas as funções puras e utiliza ao longo do processamento funções de ordem superior, visando um bom entendimento e até mesmo uma performance melhor.

- O programa possui uma interface simples e de fácil uso, onde os usuários definem o valor a ser convertido, escolhem a moeda de origem e a moeda final. após clicar em converter será apresentado uma mensagem abaixo do botão de converter apresentando o valor convertido na moeda desejada.
![Interface](/public/imgInterface.png)

## Sobre a API awesomeapi

- É uma API gratuita que fornece informações sobre as cotações de diversar moedas diferentes, é possível acessar a documentação pelo link `https://docs.awesomeapi.com.br/api-de-moedas`.

- Pontos positivos:
    - Não solicita credencias, o que facilita o uso.
    - Segundo a documentação, as informações são atualizadas a cada 30 segundos.

- Pontos negativos:
    - A api fornece o endpoint: `https://economia.awesomeapi.com.br/xml/available/uniq` que fornece todos os códigos de moedas reconhecidos pela api, e fornece o endpoint `https://economia.awesomeapi.com.br/xml/available`, que fornece as possíveis conversões que a api disponibiliza. A questão é que nem todas as moedas disponibilizadas no endpoint 1 estão disponíveis para conversão.

### O que foi utilizado

- O endpoint: `https://economia.awesomeapi.com.br/xml/available/uniq`, para buscar as moedas e criar as opções dos campos de lista.

- O endpoint: `https://economia.awesomeapi.com.br/xml/available` não foi implementado no código, mas é importante visualizar o mesmo para saber as conversões disponibilizadas pela api.

- O endpoint que busca as informações de cotações das moedas. Ex de conversão de Dolar para real: `https://economia.awesomeapi.com.br/last/USD-BRL`.

## Orientações para simular o projeto

1. Necessário ter o php instalado e um localHost configurado, Ex: Xampp.
    - Se utilizado o xampp, é importante lembrar que o projeto deve estar armazendo dentro da pasta htdocs do xampp.
    - Iniciar o apache, acessar o locaHost da sua máquina através do navegador e ir até o diretório "public" do projeto.

2. Após configurar o ambiente, e chegar na interface do site acesse o link `https://economia.awesomeapi.com.br/xml/available` para verificar as conversões disponibilizadas pela api.

3. Validações dados de entrada:
    - Informe um valor para conversão mas não selecione nenhuma moeda para conversão, observe que a mensagem `É necessário selecionar duas moedas para realizar a conversão!` é apresentada. 
    - Selecione apenas 1 moeda, seja ela de origem ou destino e observe que a mensagem ainda é apresentada.
    - Selecione duas moedas iguais, Ex: converter de Euro para Euro, observe que a mensagem `A moeda de origem não pode ser a mesma que a moeda de destino.` é apresentada.
    - Tente converter sem informar nenhum valor, observe que é solicitado que o campo de Valor seja preenchido.
    - Informe o valor 0, selecione duas moedas diferentes que possam ser convertidas, observe que a mensagem `O valor deve ser um número maior que zero.` é apresentada.
    - Observe que não é possível informe qualquer caracter que não seja um inteiro no campo de valor.
    - Por fim, informe todos os dados corretos e verifique que a conversão está sendo realizada.