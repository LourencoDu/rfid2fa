const form = document.getElementById("form");
let modal;

function openReadCardModal() {
  modal = document.getElementById("read-card-modal");
  modal.classList.remove("hidden");
  modal.classList.add("relative");
}

function closeReadCardModal() {
  modal.classList.remove("relative");
  modal.classList.add("hidden");
}

function submitForm() {
  openReadCardModal();
  //form.submit();
}