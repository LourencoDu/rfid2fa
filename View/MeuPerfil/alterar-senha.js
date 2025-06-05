const form = document.getElementById("form-modal-form");
let isOpen = false;

const validators = {
  senha: (val) => /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(val),
  required: (val) => !!val.trim(),
};

document
  .getElementById("button-alterar-senha")
  .addEventListener("click", () => {
    openFormModal({
      title: "Alterar Senha",
      onConfirm: (event) => onSubmitForm(event),
      showCloseButton: true,
      campos: [
        {
          name: "senha-atual",
          label: "Senha Atual",
          type: "password",
          validate: "required",
          maxLength: 50,
          isRequired: true,
          helperText: "Digite a sua senha atual.",
        },
        {
          name: "senha-nova",
          label: "Nova Senha",
          type: "password",
          validate: "senha",
          maxLength: 50,
          isRequired: true,
          helperText:
            "A senha deve conter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e símbolos.",
        },
      ],
    });

    form.querySelectorAll("[data-validate]").forEach((input) => {
      function validate() {
        const tipo = input.dataset.validate;
        const valido = validators[tipo](input.value);
        setError(input, !valido);
      }
      input.removeEventListener("input", validate);
      input.addEventListener("input", validate);
    });
  });

function handleSucesso() {
  showSnackbar("Senha alterada com sucesso!", "success");
  closeFormModal();
  closeReadCardModal();
}

function handleFalha(erro = "", mensagem = "") {
  if (!!erro || !!mensagem) {
    showSnackbar(mensagem, "erro", 5000);
  } else {
    showSnackbar("Falha ao alterar senha", "erro", 5000);
  }
  closeReadCardModal();
  closeFormModal();
}

async function handleAlterarSenha() {
  setTimeout(async () => {
    const formData = new FormData(form);

    const senhaAtual = formData.get("senha-atual");
    const senhaNova = formData.get("senha-nova");

    const response = await post(
      "/usuario/alterar-senha",
      new URLSearchParams({
        "senha-atual": senhaAtual,
        "senha-nova": senhaNova,
      })
    );

    if (response.status === "success") {
      handleSucesso();
    } else {
      const erro = response.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        handleAlterarSenha();
      } else {
        handleFalha(erro, response.mensagem);
      }
    }
  }, 2500);
}

async function onSubmitForm(event) {
  event.preventDefault();

  let valido = true;

  form.querySelectorAll("[data-validate]").forEach((input) => {
    const tipo = input.dataset.validate;
    const ok = validators[tipo](input.value);
    setError(input, !ok);
    if (!ok) valido = false;
  });

  if (valido) {
    const form = event.target;
    const dados = new FormData(form);

    const senhaAtual = dados.get("senha-atual");
    const senhaNova = dados.get("senha-nova");

    toggleLoading(true);

    const response = await post(
      "/usuario/alterar-senha",
      new URLSearchParams({
        "senha-atual": senhaAtual,
        "senha-nova": senhaNova,
      })
    );

    toggleLoading(false);

    if (response?.status === "success") {
      handleSucesso();
    } else {
      const erro = response?.erros[0];

      if (erro == "aguardando-leitura-cartao") {
        handleAlterarSenha();

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
}
