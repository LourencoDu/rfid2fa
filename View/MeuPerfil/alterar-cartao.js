let isOpenAlterarCartao = false;

document
  .getElementById("button-alterar-cartao")
  .addEventListener("click", async () => {
    toggleLoading(true);

    const response = await post(
      "/usuario/alterar-cartao"
    );

    toggleLoading(false);

    if (response?.status === "success") {
      handleSucesso();
    } else {
      const erro = response?.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        handleAlterarCartao();

        isOpenAlterarCartao = true;
        openReadCardModal({
          onCancel: () => {
            isOpenAlterarCartao = false
          },
        });
      } else {
        handleFalha(erro, response.mensagem);
      }
    }
  });

function handleSucesso() {
  showSnackbar("Cartão cadastrado alterado com sucesso!", "success");
  closeReadCardModal();
}

function handleFalha(erro = "", mensagem = "") {
  if (!!erro || !!mensagem) {
    showSnackbar(mensagem, "erro", 5000);
  } else {
    showSnackbar("Falha ao alterar o cartão cadastrado", "erro", 5000);
  }
  closeReadCardModal();
}

async function handleAlterarCartao() {
  setTimeout(async () => {
    const response = await post(
      "/usuario/alterar-cartao"
    );

    if (response.status === "success") {
      handleSucesso();
    } else {
      const erro = response.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        handleAlterarCartao();
      } else {
        handleFalha(erro, response.mensagem);
      }
    }
  }, 2500);
}
