# 🔄 Migração Completa para PHP - Sistema de Login + Estoque

## 📋 Resumo das Mudanças

Seu sistema foi **completamente convertido de Node.js para PHP**, mantendo toda a funcionalidade e adicionando autenticação de usuários.

---

## 📁 Arquivos Novos e Atualizados

### **Arquivos PHP Principais:**

| Arquivo | Função |
|---------|--------|
| `config.php` | Conexão com banco + inicialização de sessão |
| `index_login.php` | Página de login |
| `cadastro.php` | Página de registro |
| `dashboard.php` | Página de boas-vindas após login |
| `editar_perfil.php` | Edição de dados do usuário |
| `alterar_senha.php` | Alteração de senha |
| `logout.php` | Saída do sistema |
| `api.php` | API REST para gerenciamento de produtos |
| `dashboard_produtos.php` | Dashboard integrado (Login + Gerenciador) |
| `verificar_sessao.php` | Verifica autenticação (retorna JSON) |

---

## 🚀 Fluxo de Funcionamento

```
┌──────────────────────────────────────────┐
│         Página de Login                  │
│      (index_login.php)                   │
└──────────────┬──────────────────────────┘
               │
               ├─► Novo Usuário? ──┐
               │                   │
               │                   ▼
               │        Página de Cadastro
               │        (cadastro.php)
               │                   │
               └─────────┬─────────┘
                         │
                         ▼
        ┌───────────────────────────────────┐
        │ Dashboard com Gerenciador         │
        │ (dashboard_produtos.php)          │
        │                                   │
        │ ✓ Autenticado via session         │
        │ ✓ Acesso à API REST               │
        │ ✓ CRUD de Produtos                │
        └───────────────┬───────────────────┘
                        │
                        ├─► Editar Perfil
                        ├─► Alterar Senha
                        └─► Logout
```

---

## 🔧 Estrutura da Autenticação

### **Fluxo de Login:**

1. Usuário acessa `index_login.php`
2. Submete email + senha
3. PHP verifica credenciais no banco (`tb_usuarios`)
4. Se válidas: cria sessão (`$_SESSION`)
5. Redireciona para `dashboard_produtos.php`
6. JavaScript verifica sessão via `verificar_sessao.php`
7. Se não autenticado: redireciona para `index_login.php`

### **Segurança:**

✅ Senhas criptografadas com **bcrypt**  
✅ **Prepared Statements** contra SQL Injection  
✅ **Sessions** para manter autenticação  
✅ **CORS headers** para requisições AJAX  
✅ Verificação de autenticação em cada requisição da API  

---

## 📝 Configuração

### **Passo 1: Criar Banco de Dados**

```sql
-- Criar banco
CREATE DATABASE sistema_estoque;
USE sistema_estoque;

-- Executar SQL dos produtos
SOURCE BDESTOQUE.sql;

-- Executar SQL dos usuários
SOURCE SQL/criar_usuarios.sql;
```

### **Passo 2: Configurar Conexão**

Edite `config.php`:

```php
// LOCAL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_estoque');

// ONLINE (substitua com dados da hospedagem)
define('DB_HOST', 'seu-servidor.com');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'seu_banco');
```

### **Passo 3: Testar**

Acesse em seu navegador:
```
http://localhost/dashboard_produtos.php
```

Se não estiver logado, será redirecionado para:
```
http://localhost/index_login.php
```

---

## 🔐 Dados de Teste

**Email:** `teste@exemplo.com`  
**Senha:** `123456`

---

## 📡 API REST (em PHP)

Todas as requisições requerem autenticação (sessão ativa).

### **GET /api.php/produtos**
Listar todos os produtos
```javascript
fetch('api.php/produtos')
  .then(r => r.json())
  .then(data => console.log(data));
```

### **POST /api.php/produtos**
Criar novo produto
```javascript
fetch('api.php/produtos', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        sku: 'NOTE001',
        nome: 'Notebook',
        preco: 2999.99,
        qtde: 10,
        codCat: 'INF',
        dataValidade: null
    })
})
```

### **PUT /api.php/produtos/:sku**
Atualizar produto
```javascript
fetch('api.php/produtos/NOTE001', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        nome: 'Notebook Novo',
        preco: 3299.99
    })
})
```

### **DELETE /api.php/produtos/:sku**
Deletar produto
```javascript
fetch('api.php/produtos/NOTE001', {
    method: 'DELETE'
})
```

---

## 🎯 Páginas Públicas vs Protegidas

### **Páginas Públicas (Sem Login):**
- `index_login.php` - Login
- `cadastro.php` - Registrar novo usuário

### **Páginas Protegidas (Requer Login):**
- `dashboard_produtos.php` - Sistema de estoque
- `dashboard.php` - Perfil do usuário
- `editar_perfil.php` - Editar dados
- `alterar_senha.php` - Mudar senha
- `api.php` - API de produtos

---

## 🛠️ Customização

### **Alterar Cores do Sistema**

Procure por estes valores no CSS:
- `#667eea` - Cor principal (roxo)
- `#764ba2` - Cor secundária (roxo escuro)

### **Adicionar Novo Campo ao Produto**

1. Adicione coluna no banco:
```sql
ALTER TABLE TPPRODUTO ADD COLUMN descricao TEXT;
```

2. Atualize `api.php` (GET, POST, PUT)
3. Atualize `dashboard_produtos.php` (formulário + exibição)

### **Adicionar Novo Campo ao Usuário**

1. Adicione coluna no banco:
```sql
ALTER TABLE tb_usuarios ADD COLUMN telefone VARCHAR(20);
```

2. Atualize `cadastro.php` (formulário)
3. Atualize `editar_perfil.php` (formulário)
4. Atualize `config.php` (se necessário)

---

## 📋 Checklist de Deploy

- [ ] Banco de dados criado
- [ ] SQL dos produtos executado
- [ ] SQL dos usuários executado
- [ ] `config.php` configurado com credenciais
- [ ] Todos os `.php` enviados para `public_html`
- [ ] Teste de login/cadastro funcionando
- [ ] Teste de CRUD de produtos funcionando
- [ ] HTTPS configurado (em produção)
- [ ] Usuário teste deletado (em produção)
- [ ] Backup automático ativado

---

## ⚠️ Diferenças do Node.js

| Node.js | PHP |
|---------|-----|
| Express.js | Funções nativas do PHP |
| Async/Await | Preparadas para sincronismo |
| Classes JavaScript | Funções simples |
| .env | config.php |
| Pool de conexões | MySQLi |
| req/res | $_SERVER / GET/POST |

---

## 🐛 Troubleshooting

### **Erro: "Não autorizado"**
- Verifique se você fez login
- Sessão pode ter expirado, faça login novamente

### **Erro: "Tabela não encontrada"**
- Execute o SQL em `SQL/criar_usuarios.sql`
- Verifique se o banco está selecionado

### **Erro: "Falha ao conectar"**
- Verifique credenciais em `config.php`
- MySQL/MariaDB está rodando?

### **Requisições AJAX retornam 401**
- Verifique se `verificar_sessao.php` retorna autenticado
- Cookies de sessão estão ativados no navegador?

---

## 📚 Estrutura do Projeto Final

```
projeto/
├── config.php                    # Configuração (IMPORTANTE)
├── verificar_sessao.php          # Verificação de autenticação
├── api.php                       # API REST dos produtos
│
├── index_login.php               # Login
├── cadastro.php                  # Cadastro
├── dashboard.php                 # Perfil
├── editar_perfil.php             # Editar dados
├── alterar_senha.php             # Mudar senha
├── logout.php                    # Sair
│
├── dashboard_produtos.php        # Dashboard integrado (PÁGINA PRINCIPAL)
│
├── SQL/
│   ├── BDESTOQUE.sql            # Estrutura de produtos
│   └── criar_usuarios.sql       # Estrutura de usuários
│
├── README_LOGIN.md              # Guia do sistema de login
└── README_MIGRACAO.md           # Este arquivo
```

---

## ✅ Próximos Passos

1. **Configure `config.php`** com suas credenciais
2. **Crie o banco de dados** e execute os SQLs
3. **Teste o login** com `teste@exemplo.com` / `123456`
4. **Crie sua conta** via cadastro
5. **Gerencie produtos** no dashboard

---

## 📞 Notas Finais

- Este sistema é **100% compatível com PHP 7.4+**
- Funciona em **qualquer hospedagem PHP com MySQL/MariaDB**
- **Seguro para produção** com as devidas configurações
- **Escalável** para adicionar novas funcionalidades
- **Mantém toda a lógica original** do Node.js

**Bom desenvolvimento!** 🚀
