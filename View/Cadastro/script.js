const form = document.getElementById("form");
let isOpen = false;

const validators = {
  nome: (val) => val.trim().length >= 2,
  email: (val) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val),
  senha: (val) => /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(val),
};

function handleSucesso() {
  window.location = "/rfid2fa/cadastro/bem-vindo";
}

function handleFalha(erro = "", mensagem = "") {
  if (!!erro || !!mensagem) {
    showSnackbar(mensagem, "erro", 5000);
  } else {
    showSnackbar(
      "Falha ao realizar o cadastro. Verifique os LOGs",
      "erro",
      5000
    );
  }
  closeReadCardModal();
}

form.querySelectorAll("[data-validate]").forEach((input) => {
  input.addEventListener("input", () => {
    const tipo = input.dataset.validate;
    const valido = validators[tipo](input.value);
    setError(input, !valido);
  });
});

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

    const nome = formData.get("nome").trim();
    const email = formData.get("email").trim();
    const senha = formData.get("senha").trim();

    toggleLoading(true);

    const response = await post(
      "/cadastro",
      new URLSearchParams({
        nome,
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
            isOpen = false
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
    if(!isOpen) return
    const formData = new FormData(form);

  const nome = formData.get("nome").trim();
  const email = formData.get("email").trim();
  const senha = formData.get("senha").trim();

  const response = await post(
    "/cadastro",
    new URLSearchParams({
      nome,
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
  }, 2500)
}
