document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // 1. SEÇÃO CONTATO (INSERT)
    // ==========================================
    const formContato = document.getElementById('form-contato');
    const feedbackContato = document.getElementById('feedback-contato');

    if (formContato) {
        formContato.addEventListener('submit', async (event) => {
            event.preventDefault();
            const dadosFormulario = new FormData(formContato);

            try {
                const resposta = await fetch('api/salvar_contato.php', {
                    method: 'POST',
                    body: dadosFormulario
                });
                const resultado = await resposta.json();

                if (resultado.sucesso) {
                    feedbackContato.innerHTML = `<div class="alert alert-success">${resultado.mensagem}</div>`;
                    formContato.reset();
                } else {
                    feedbackContato.innerHTML = `<div class="alert alert-danger">${resultado.mensagem}</div>`;
                }
            } catch (erro) {
                console.error('Erro no contato:', erro);
                feedbackContato.innerHTML = `<div class="alert alert-danger">Erro de comunicação com o servidor.</div>`;
            }
        });
    }

    // ==========================================
    // 2. SEÇÃO DEPOIMENTOS (SELECT + INSERT)
    // ==========================================
    const listaDepoimentos = document.getElementById('lista-depoimentos');
    const formDepoimento = document.getElementById('form-depoimento');
    const feedbackDepoimento = document.getElementById('feedback-depoimento');

    // Função que busca os depoimentos no PHP e monta os cards HTML
    async function carregarDepoimentos() {
        if (!listaDepoimentos) return;

        try {
            const resposta = await fetch('api/depoimentos.php');
            const resultado = await resposta.json();

            if (resultado.sucesso) {
                listaDepoimentos.innerHTML = ''; // Limpa o container antes de preencher

                resultado.dados.forEach(dep => {
                    // Se tiver foto no banco, exibe a <img>; se for null, exibe o ícone padrão
                    const imagemHTML = dep.foto_url 
                        ? `<img src="${dep.foto_url}" alt="${dep.nome}" class="rounded-circle me-3" style="width: 70px; height: 70px; object-fit: cover;">`
                        : `<div class="rounded-circle bg-secondary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                             <i class="bi bi-person-fill"></i>
                           </div>`;

                    const cardHTML = `
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm p-3">
                                <div class="d-flex align-items-center mb-3">
                                    ${imagemHTML}
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">${dep.nome}</h6>
                                        <small class="text-muted d-block">${dep.perfil}</small>
                                        <small class="text-secondary">${dep.data_formatada}</small>
                                    </div>
                                </div>
                                <p class="card-text text-secondary fst-italic">
                                    "${dep.comentario}"
                                </p>
                            </div>
                        </div>
                    `;
                    listaDepoimentos.innerHTML += cardHTML;
                });
            }
        } catch (erro) {
            console.error('Erro ao carregar depoimentos:', erro);
        }
    }

    // Chama a listagem assim que a página abre
    carregarDepoimentos();

    // Envio de novo depoimento
    if (formDepoimento) {
        formDepoimento.addEventListener('submit', async (event) => {
            event.preventDefault();
            const dadosFormulario = new FormData(formDepoimento);

            try {
                const resposta = await fetch('api/depoimentos.php', {
                    method: 'POST',
                    body: dadosFormulario
                });
                const resultado = await resposta.json();

                if (resultado.sucesso) {
                    feedbackDepoimento.innerHTML = `<div class="alert alert-success">${resultado.mensagem}</div>`;
                    formDepoimento.reset();
                    carregarDepoimentos();
                } else {
                    feedbackDepoimento.innerHTML = `<div class="alert alert-danger">${resultado.mensagem}</div>`;
                }
            } catch (erro) {
                console.error('Erro ao enviar depoimento:', erro);
                feedbackDepoimento.innerHTML = `<div class="alert alert-danger">Erro de comunicação com o servidor.</div>`;
            }
        });
    }

    // ==========================================
    // 3. SEÇÃO ORÇAMENTO (INSERT)
    // ==========================================
    const formOrcamento = document.getElementById('formOrcamentoEmpresarial');
    const feedbackOrcamento = document.getElementById('feedback-orcamento');

    if (formOrcamento) {
        formOrcamento.addEventListener('submit', async (event) => {
            event.preventDefault();
            const dadosFormulario = new FormData(formOrcamento); // Captura os dados do formulário

            try {
                const resposta = await fetch('api/salvar_orcamento.php', {
                    method: 'POST',
                    body: dadosFormulario
                });
                const resultado = await resposta.json(); // Converte a resposta JSON para um objeto JavaScript

                if (resultado.sucesso) {
                    feedbackOrcamento.innerHTML = `<div class="alert alert-success">${resultado.mensagem}</div>`;
                    formOrcamento.reset();
                } else {
                    feedbackOrcamento.innerHTML = `<div class="alert alert-danger">${resultado.mensagem}</div>`;
                }
            } catch (erro) {
                console.error('Erro ao enviar orçamento:', erro);
                feedbackOrcamento.innerHTML = `<div class="alert alert-danger">Erro de comunicação com o servidor.</div>`;
            }
        });
    }
});