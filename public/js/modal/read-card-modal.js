const readCardModal = document.getElementById("read-card-modal");

function openReadCardModal({ title, text, cancelButtonText, onCancel }) {
  const titleEl = document.getElementById("read-card-modal-title");
  const textEl = document.getElementById("read-card-modal-text");
  const cancelButtonEl = document.getElementById("read-card-modal-cancel-button");

  if(title) {
    titleEl.innerText = title;
  }

  if(text) {
    textEl.innerText = text;
  }

  if(cancelButtonText) {
    cancelButtonEl.innerText = cancelButtonText;
  }

  if(!!onCancel) {
    cancelButtonEl.onclick = () => {
      onCancel();
      closeReadCardModal();
    }
  }

  readCardModal.classList.toggle("hidden", false);  
}

function closeReadCardModal() {
  readCardModal.classList.toggle("hidden", true);
}

function setReadCardModalIsLoading(isLoading = false) {
  const cancelButtonEl = document.getElementById("read-card-modal-cancel-button");
  const confirmButtonEl = document.getElementById("read-card-modal-confirm-button");

  cancelButtonEl.disabled  = isLoading;
  confirmButtonEl.disabled  = isLoading;

  toggleLoading(isLoading);
}