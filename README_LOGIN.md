# Sistema de Login em PHP - Guia Completo

## 📋 Descrição
Sistema completo de autenticação de usuários em PHP com suporte a MySQL/MariaDB, ideal para hospedagem online e PhpMyAdmin.

## 📁 Arquivos do Sistema

### 1. **criar_usuarios.sql**
Script SQL para criar a tabela de usuários no banco de dados.
- Cria tabela `tb_usuarios`
- Define índice no email
- Inclui usuário de teste (teste@exemplo.com / 123456)

### 2. **config.php**
Arquivo de configuração centralizado.
- Conexão com banco de dados MySQL
- Inicialização de sessão
- Configurações de segurança

### 3. **index_login.php**
Página de login do sistema.
- Validação de email e senha
- Verificação de credenciais com bcrypt
- Registro de último login
- Redirecionamento para dashboard

### 4. **cadastro.php**
Página de registro de novos usuários.
- Validação de dados
- Verificação de email duplicado
- Criptografia de senha com bcrypt
- Design responsivo

### 5. **dashboard.php**
Página protegida após login.
- Informações da conta
- Botões de ação (editar perfil, alterar senha)
- Dicas de segurança

### 6. **editar_perfil.php**
Página para atualizar dados do usuário.
- Edição de nome e email
- Validação de email único
- Atualização de sessão

### 7. **alterar_senha.php**
Página para mudar a senha.
- Verificação da senha atual
- Validação da nova senha
- Criptografia segura com bcrypt

### 8. **logout.php**
Página de logout.
- Destruição de sessão
- Limpeza de cookies
- Redirecionamento para login

## 🚀 Instalação e Configuração

### Passo 1: Criar o Banco de Dados
1. Abra PhpMyAdmin
2. Crie um novo banco de dados (ex: `sistema_estoque`)
3. Execute o SQL do arquivo `criar_usuarios.sql`

### Passo 2: Configurar Conexão
Edite o arquivo `config.php`:

```php
// Para hospedagem LOCAL:
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_estoque');

// Para hospedagem ONLINE:
define('DB_HOST', 'seu_servidor.com.br'); // Fornecido pela hospedagem
define('DB_USER', 'seu_usuario');          // Fornecido pela hospedagem
define('DB_PASS', 'sua_senha');            // Fornecido pela hospedagem
define('DB_NAME', 'seu_banco');            // Fornecido pela hospedagem
```

### Passo 3: Upload para Hospedagem
1. Copie todos os arquivos `.php` para a pasta `public_html` da hospedagem
2. Certifique-se de que o `config.php` tem as credenciais corretas
3. A pasta deve estar acessível via navegador

## 🔐 Recursos de Segurança

✅ **Criptografia de Senha**: Utiliza bcrypt (`PASSWORD_BCRYPT`)
✅ **Prepared Statements**: Proteção contra SQL Injection
✅ **Validação de Email**: Verifica formato válido
✅ **Sessão Segura**: Usar HTTPS em produção
✅ **Sanitização**: Uso de `htmlspecialchars()` contra XSS
✅ **Índice no Email**: Melhora performance nas buscas

## 📱 Fluxo de Uso

```
┌─────────────────┐
│  Novo Usuário?  │
└────────┬────────┘
         │
    ┌────▼────┐
    │  NÃO    │  SIM
    │         ├──────────┐
    │         │          │
    ▼         ▼          ▼
 Login    Cadastro   Criar Conta
    │         │          │
    ├─────────┴──────────┘
    │
    ▼
Dashboard (Protegido)
    │
    ├─ Editar Perfil
    ├─ Alterar Senha
    └─ Logout
```

## 🔑 Dados de Teste

**Email**: teste@exemplo.com
**Senha**: 123456

## ⚙️ Configuração para Hospedagem Online

### Passo a Passo:

1. **Solicitar Dados de Banco:**
   Contate seu provedor de hospedagem para obter:
   - Host do banco
   - Usuário do banco
   - Senha do banco
   - Nome do banco

2. **Atualizar config.php:**
   ```php
   define('DB_HOST', 'host_fornecido_pela_hospedagem');
   define('DB_USER', 'usuario_fornecido');
   define('DB_PASS', 'senha_fornecida');
   define('DB_NAME', 'banco_fornecido');
   ```

3. **Executar SQL:**
   - Acesse PhpMyAdmin da hospedagem
   - Selecione seu banco de dados
   - Vá na aba "SQL"
   - Cole e execute o conteúdo de `criar_usuarios.sql`

4. **Upload de Arquivos:**
   - Via FTP: Upload todos os `.php` para `public_html`
   - Testear acesso: `www.seusite.com/index_login.php`

## 🔒 Boas Práticas

1. **Use HTTPS**: Em produção, sempre use conexão segura
2. **Altere Credenciais Padrão**: Não deixe usuario teste ativo
3. **Backup Regular**: Faça backup do banco de dados frequentemente
4. **Atualizações**: Mantenha PHP e MySQL atualizados
5. **Permissões**: Verifique permissões de arquivo (.php deve ser 644)

## 🐛 Solução de Problemas

### Erro: "Erro ao conectar com o banco de dados"
- Verifique credenciais em `config.php`
- Confirme que o banco de dados foi criado
- Verifique se o servidor MySQL está rodando

### Erro: "Email já cadastrado"
- Significa que o email já existe
- Use outro email ou faça login

### Erro: "SQL Injection detected"
- Não é um erro real, segurança está funcionando
- Não tente inserir caracteres especiais em excesso

### Página em branco
- Ative exibição de erros temporariamente em `config.php`:
  ```php
  ini_set('display_errors', 1);
  ```

## 📊 Estrutura da Tabela

```sql
tb_usuarios:
├── id (INT, PK)
├── nome (VARCHAR 100)
├── email (VARCHAR 100, UNIQUE)
├── senha (VARCHAR 255)
├── data_criacao (TIMESTAMP)
├── data_ultima_login (TIMESTAMP)
└── ativo (TINYINT)
```

## 🎨 Customização

### Alterar Cores:
Procure por `#667eea` e `#764ba2` nos arquivos `.php`

### Alterar Nome do Sistema:
Procure por "Sistema de Estoque" nos arquivos `.php`

### Adicionar Campos:
1. Altere a tabela com SQL `ALTER TABLE`
2. Atualize o formulário HTML
3. Atualize os `$_POST` de processamento

## 📞 Suporte

Para dúvidas sobre hospedagem:
- Contate o suporte da sua hospedagem
- Verifique a documentação PHP do servidor
- Teste localmente antes de fazer upload

## ✅ Checklist de Deploy

- [ ] Banco de dados criado e SQL executado
- [ ] `config.php` com credenciais corretas
- [ ] Todos os arquivos `.php` enviados
- [ ] Testes de login/cadastro funcionando
- [ ] Usuário teste deletado (em produção)
- [ ] HTTPS configurado
- [ ] Backups automáticos ativados

---

**Versão**: 1.0
**Compatibilidade**: PHP 7.4+, MySQL 5.7+, MariaDB 10.3+
**Última atualização**: Dezembro 2024
