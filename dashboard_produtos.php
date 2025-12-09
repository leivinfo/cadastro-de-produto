<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Estoque</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 2em;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            opacity: 0.9;
        }

        .btn-logout {
            background: white;
            color: #667eea;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }

            header {
                flex-direction: column;
                gap: 15px;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }
        }

        .form-section, .products-section {
            display: flex;
            flex-direction: column;
        }

        h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        button {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-delete {
            background: #ff6b6b;
            padding: 8px 12px;
            font-size: 0.9em;
            width: auto;
        }

        .btn-delete:hover {
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-update {
            background: #51cf66;
            padding: 8px 12px;
            font-size: 0.9em;
            width: auto;
            margin-right: 5px;
        }

        .btn-update:hover {
            box-shadow: 0 5px 15px rgba(81, 207, 102, 0.4);
        }

        .product-item {
            background: #f9f9f9;
            border: 1px solid #eee;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: box-shadow 0.3s;
        }

        .product-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-item h3 {
            color: #333;
            margin-bottom: 8px;
        }

        .product-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.95em;
        }

        .product-info p {
            color: #666;
        }

        .product-info strong {
            color: #333;
        }

        .product-actions {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #667eea;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .toggle-form {
            display: none;
        }

        .toggle-form.visible {
            display: block;
        }

        .form-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .form-tabs button {
            flex: 1;
            padding: 10px;
            background: #f0f0f0;
            color: #333;
            border: 2px solid transparent;
        }

        .form-tabs button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        #edit-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 Sistema de Estoque</h1>
            <div class="user-info">
                <span id="user-display"></span>
                <button class="btn-logout" onclick="fazerLogout()">Sair</button>
            </div>
        </header>

        <div class="content">
            <!-- SEÇÃO DE FORMULÁRIO -->
            <div class="form-section">
                <h2>Adicionar/Editar Produto</h2>
                
                <div id="message"></div>

                <div class="form-tabs">
                    <button class="tab-btn active" data-tab="create">Novo</button>
                    <button class="tab-btn" data-tab="edit">Editar</button>
                </div>

                <!-- FORM CRIAR -->
                <div id="create-form" class="toggle-form visible">
                    <div class="form-group">
                        <label for="sku">SKU *</label>
                        <input type="text" id="sku" placeholder="Ex: NOTE001" required maxlength="10">
                    </div>
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" placeholder="Ex: Notebook Dell" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço *</label>
                        <input type="number" id="preco" placeholder="Ex: 2999.99" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="qtde">Quantidade *</label>
                        <input type="number" id="qtde" placeholder="Ex: 10" required>
                    </div>
                    <div class="form-group">
                        <label for="codCat">Categoria *</label>
                        <select id="codCat" required>
                            <option value="">-- Selecione --</option>
                            <option value="INF">Informática</option>
                            <option value="ALI">Alimentos</option>
                            <option value="LIM">Limpeza</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dataValidade">Data de Validade (Perecível)</label>
                        <input type="date" id="dataValidade">
                    </div>
                    <button onclick="criarProduto()">Criar Produto</button>
                </div>

                <!-- FORM EDITAR -->
                <div id="edit-form" class="toggle-form">
                    <div class="form-group">
                        <label for="editSku">SKU para editar *</label>
                        <input type="text" id="editSku" placeholder="Ex: NOTE001" required maxlength="10">
                    </div>
                    <button onclick="procurarParaEditar()" style="margin-bottom: 20px;">🔍 Procurar</button>

                    <div id="edit-fields">
                        <div class="form-group">
                            <label for="editNome">Nome</label>
                            <input type="text" id="editNome" placeholder="Deixe vazio para não alterar">
                        </div>
                        <div class="form-group">
                            <label for="editPreco">Preço</label>
                            <input type="number" id="editPreco" placeholder="Deixe vazio para não alterar" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="editQtde">Quantidade</label>
                            <input type="number" id="editQtde" placeholder="Deixe vazio para não alterar">
                        </div>
                        <div class="form-group">
                            <label for="editCodCat">Categoria</label>
                            <select id="editCodCat">
                                <option value="">-- Não alterar --</option>
                                <option value="INF">Informática</option>
                                <option value="ALI">Alimentos</option>
                                <option value="LIM">Limpeza</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDataValidade">Data de Validade</label>
                            <input type="date" id="editDataValidade">
                        </div>
                        <button onclick="atualizarProduto()" style="background: #51cf66;">✏️ Atualizar</button>
                        <button onclick="cancelarEdicao()" class="btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO DE PRODUTOS -->
            <div class="products-section">
                <h2>Produtos Cadastrados</h2>
                <button onclick="carregarProdutos()" style="margin-bottom: 15px;">🔄 Atualizar Lista</button>
                
                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Carregando...</p>
                </div>

                <div id="products-list"></div>
            </div>
        </div>
    </div>

    <script>
        // ===== VERIFICAR AUTENTICAÇÃO E CARREGAR DADOS DO USUÁRIO =====
        async function verificarAutenticacao() {
            try {
                const response = await fetch('verificar_sessao.php');
                const dados = await response.json();
                
                if (dados.autenticado) {
                    document.getElementById('user-display').textContent = 
                        `👤 ${dados.usuario_nome} (${dados.usuario_email})`;
                } else {
                    window.location.href = 'index_login.php';
                }
            } catch (error) {
                window.location.href = 'index_login.php';
            }
        }

        // ===== TAB SWITCHING =====
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.getAttribute('data-tab');
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                document.getElementById('create-form').classList.remove('visible');
                document.getElementById('edit-form').classList.remove('visible');
                document.getElementById(`${tab}-form`).classList.add('visible');
            });
        });

        // ===== CRIAR PRODUTO =====
        async function criarProduto() {
            const produto = {
                sku: document.getElementById('sku').value.trim(),
                nome: document.getElementById('nome').value.trim(),
                preco: parseFloat(document.getElementById('preco').value),
                qtde: parseInt(document.getElementById('qtde').value),
                codCat: document.getElementById('codCat').value,
                dataValidade: document.getElementById('dataValidade').value || null
            };

            if (!produto.sku || !produto.nome || !produto.preco || !produto.qtde || !produto.codCat) {
                showMessage('Preencha todos os campos obrigatórios!', 'error');
                return;
            }

            if (produto.sku.length > 10) {
                showMessage('SKU não pode ter mais de 10 caracteres', 'error');
                return;
            }

            try {
                showLoading(true);
                const response = await fetch('api.php/produtos', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(produto)
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(`✅ Produto "${produto.nome}" criado com sucesso!`, 'success');
                    document.getElementById('sku').value = '';
                    document.getElementById('nome').value = '';
                    document.getElementById('preco').value = '';
                    document.getElementById('qtde').value = '';
                    document.getElementById('codCat').value = '';
                    document.getElementById('dataValidade').value = '';
                    carregarProdutos();
                } else {
                    showMessage(`❌ Erro: ${data.mensagem}`, 'error');
                }
            } catch (error) {
                showMessage(`❌ Erro de conexão: ${error.message}`, 'error');
            } finally {
                showLoading(false);
            }
        }

        // ===== PROCURAR PARA EDITAR =====
        async function procurarParaEditar() {
            const sku = document.getElementById('editSku').value.trim();
            if (!sku) {
                showMessage('Digite o SKU do produto', 'error');
                return;
            }

            try {
                showLoading(true);
                const response = await fetch('api.php/produtos');
                const produtos = await response.json();
                
                const produtoDesc = produtos.find(p => p.includes(`SKU: ${sku}`));
                
                if (produtoDesc) {
                    document.getElementById('edit-fields').style.display = 'block';
                    showMessage(`Produto encontrado! Edite os campos desejados.`, 'info');
                } else {
                    showMessage(`Produto com SKU "${sku}" não encontrado`, 'error');
                    document.getElementById('edit-fields').style.display = 'none';
                }
            } catch (error) {
                showMessage(`❌ Erro: ${error.message}`, 'error');
            } finally {
                showLoading(false);
            }
        }

        // ===== ATUALIZAR PRODUTO =====
        async function atualizarProduto() {
            const sku = document.getElementById('editSku').value.trim();
            const updates = {};

            const nome = document.getElementById('editNome').value.trim();
            if (nome) updates.nome = nome;

            const preco = document.getElementById('editPreco').value;
            if (preco) updates.preco = parseFloat(preco);

            const qtde = document.getElementById('editQtde').value;
            if (qtde) updates.qtde = parseInt(qtde);

            const codCat = document.getElementById('editCodCat').value;
            if (codCat) updates.codCat = codCat;

            const dataValidade = document.getElementById('editDataValidade').value;
            if (dataValidade) updates.dataValidade = dataValidade;

            if (Object.keys(updates).length === 0) {
                showMessage('Edite pelo menos um campo!', 'error');
                return;
            }

            try {
                showLoading(true);
                const response = await fetch(`api.php/produtos/${sku}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updates)
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(`✅ Produto atualizado com sucesso!`, 'success');
                    cancelarEdicao();
                    carregarProdutos();
                } else {
                    showMessage(`❌ Erro: ${data.mensagem}`, 'error');
                }
            } catch (error) {
                showMessage(`❌ Erro de conexão: ${error.message}`, 'error');
            } finally {
                showLoading(false);
            }
        }

        // ===== CANCELAR EDIÇÃO =====
        function cancelarEdicao() {
            document.getElementById('editSku').value = '';
            document.getElementById('editNome').value = '';
            document.getElementById('editPreco').value = '';
            document.getElementById('editQtde').value = '';
            document.getElementById('editCodCat').value = '';
            document.getElementById('editDataValidade').value = '';
            document.getElementById('edit-fields').style.display = 'none';
        }

        // ===== DELETAR PRODUTO =====
        async function deletarProduto(sku) {
            if (!confirm(`Tem certeza que deseja deletar o produto com SKU "${sku}"?`)) {
                return;
            }

            try {
                showLoading(true);
                const response = await fetch(`api.php/produtos/${sku}`, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(`✅ Produto deletado com sucesso!`, 'success');
                    carregarProdutos();
                } else {
                    showMessage(`❌ Erro: ${data.mensagem}`, 'error');
                }
            } catch (error) {
                showMessage(`❌ Erro de conexão: ${error.message}`, 'error');
            } finally {
                showLoading(false);
            }
        }

        // ===== CARREGAR PRODUTOS =====
        async function carregarProdutos() {
            try {
                showLoading(true);
                const response = await fetch('api.php/produtos');
                const produtos = await response.json();

                const lista = document.getElementById('products-list');

                if (produtos.length === 0) {
                    lista.innerHTML = '<p style="color: #999; text-align: center;">Nenhum produto cadastrado</p>';
                    return;
                }

                lista.innerHTML = produtos.map((produtoDesc, idx) => {
                    const skuMatch = produtoDesc.match(/SKU: (\S+)/);
                    const sku = skuMatch ? skuMatch[1] : `prod-${idx}`;

                    return `
                        <div class="product-item">
                            <h3>${produtoDesc.split(' | ')[0].replace('Produto: ', '')}</h3>
                            <div class="product-info">
                                <p><strong>SKU:</strong> ${sku}</p>
                                <p><strong>Descrição:</strong> ${produtoDesc}</p>
                            </div>
                            <div class="product-actions">
                                <button class="btn-update" onclick="editarProduto('${sku}')">✏️ Editar</button>
                                <button class="btn-delete" onclick="deletarProduto('${sku}')">🗑️ Deletar</button>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                document.getElementById('products-list').innerHTML = `<p style="color: #f00;">Erro ao carregar produtos: ${error.message}</p>`;
            } finally {
                showLoading(false);
            }
        }

        // ===== EDITAR PRODUTO (QUICK) =====
        function editarProduto(sku) {
            document.querySelectorAll('.tab-btn')[1].click();
            document.getElementById('editSku').value = sku;
            procurarParaEditar();
        }

        // ===== LOGOUT =====
        function fazerLogout() {
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'logout.php';
            }
        }

        // ===== HELPERS =====
        function showMessage(msg, type) {
            const msgEl = document.getElementById('message');
            msgEl.className = `message ${type}`;
            msgEl.textContent = msg;
            msgEl.style.display = 'block';
            setTimeout(() => msgEl.style.display = 'none', 5000);
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }

        // ===== INICIALIZAR =====
        window.addEventListener('load', () => {
            verificarAutenticacao();
            carregarProdutos();
        });
    </script>
</body>
</html>
