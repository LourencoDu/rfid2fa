const form = document.getElementById("form");
let isOpen = false;

function handleSucesso() {
  window.location.reload();
}

function handleFalha(erro = "", mensagem = "") {
  if (!!erro || !!mensagem) {
    showSnackbar(mensagem, "erro", 5000);
  } else {
    showSnackbar("Falha ao realizar o login. Verifique os LOGs", "erro", 5000);
  }
  closeReadCardModal();
}

form.addEventListener("submit", async (event) => {
  event.preventDefault();

  let valido = true;

  form.querySelectorAll("[data-validate]").forEach((input) => {
    const tipo = input.dataset.validate;
    const ok = validators[tipo](input.value);
    setError(input, !ok);
    if (!ok) valido = false;
  });

  if (valido) {
    const formData = new FormData(event.target);

    const email = formData.get("email").trim();
    const senha = formData.get("senha").trim();

    toggleLoading(true);

    const response = await post(
      "/login",
      new URLSearchParams({
        email,
        senha,
      })
    );

    toggleLoading(false);

    if (response?.status === "success") {
      handleSucesso();
    } else {
      const erro = response?.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        verificarLeitura();

        isOpen = true;
        openReadCardModal({
          onCancel: () => {
            isOpen = false;
          },
        });
      } else {
        handleFalha(erro, response.mensagem);
      }
    }
  }
});

async function verificarLeitura() {
  setTimeout(async () => {
    if (!isOpen) return;
    const formData = new FormData(form);

    const email = formData.get("email").trim();
    const senha = formData.get("senha").trim();

    const response = await post(
      "/login",
      new URLSearchParams({
        email,
        senha,
      })
    );

    if (response.status === "success") {
      handleSucesso();
    } else {
      const erro = response.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        verificarLeitura();
      } else {
        handleFalha(erro, response.mensagem);
      }
    }
  }, 2500);
}
