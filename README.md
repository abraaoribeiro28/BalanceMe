# BalanceMe - App de Finanças Pessoais

Aplicação web para controle de receitas e despesas com gráficos comparativos.
Permite cadastrar transações por categoria e (opcionalmente) cartão de crédito,
visualizar indicadores do mês, séries históricas dos últimos meses, distribuição
de despesas por categoria e por cartão de crédito, além de uma lista paginada
de transações.

## Visão Geral

- Dashboard com saldo, total de receitas/despesas do mês e variação M/M.
- Registro rápido de transações (Receita/Despesa) via modal.
- Categorias personalizadas (Receita, Despesa ou Ambos).
- Cartões cadastráveis para associar despesas no cartão.
- Gráficos com Chart.js:
  - Série temporal (últimos 6 meses) de receitas x despesas.
  - Distribuição de despesas por categoria (pizza).
  - Distribuição de despesas por cartão (pizza).
- Autenticação completa (login, registro, verificação de e‑mail, reset de senha).

## Tecnologias

- `PHP 8.2+`, `Laravel 12`, `Livewire 3`
- `Tailwind CSS 4`, `Vite`
- `Chart.js 4`
- UI baseada em componentes SheafUI

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+ e npm
- Banco de dados (MYSQL recomendado para desenvolvimento)

## Instalação (Desenvolvimento)

1) Clonar e entrar na pasta do projeto
```
    git clone https://github.com/abraaoribeiro28/BalanceMe.git
    cd BalanceMe
```

2) Dependências PHP
```
    composer install
    
    # Copiar o env
    cp .env.example .env
    
    #Gerar key
    php artisan key:generate
```

3) Banco de dados
- Configure MySQL (recomendado) no `.env` e crie o banco.
- Ou SQLite (opção simples, não recomandado):
  - Criar arquivo: `mkdir -p database && touch database/database.sqlite`
  - No `.env`, defina: `DB_CONNECTION=sqlite` e comente demais variáveis `DB_*`.

4) Migrar e popular dados de exemplo
```
    php artisan migrate --seed
```

5) Dependências front‑end
```
    npm install
```

6) Rodar em modo dev (2 opções)
- Separado: `php artisan serve` e, em outro terminal, `npm run dev`
- Integrado (server + queue + vite): `composer run dev`

7) Acessar
- Abra `http://127.0.0.1:8000`

Credenciais de exemplo (seed):
- Email: `test@example.com`
- Senha: `password`

## Principais Telas e Fluxos

- `Dashboard` (`/dashboard`): indicadores e alternância entre abas de visão geral e transações.
- `Overview` (no dashboard): 3 gráficos (série temporal, despesas por categoria, despesas por cartão) e últimas transações.
- `Transações`: listagem paginada com categoria, data, valor, tipo e cartão.
- `Modal de Transação`: nome, valor (máscara monetária), tipo, categoria, cartão (opcional), data e descrição.
- `Categorias` e `Cartões`: cadastro e manutenção para organização das transações.

## Scripts Úteis

- `composer run dev` — inicia PHP server, fila e Vite juntos (requer Node).
- `composer test` ou `php artisan test` — executa a suíte de testes.
- `npm run dev` — build em modo desenvolvimento (Vite).
- `npm run build` — build para produção.

## Licença

Distribuído sob licença MIT. Consulte `LICENCE.md`.
