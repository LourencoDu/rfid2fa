const form = document.getElementById("form");
let modal;
let readCardInterval = null;

function openReadCardModal() {
  modal = document.getElementById("read-card-modal");
  modal.classList.remove("hidden");
  modal.classList.add("relative");

  readCardInterval = setInterval(() => {
    console.log("chamada da api...");
  }, 2000)
}

function closeReadCardModal() {
  modal.classList.remove("relative");
  modal.classList.add("hidden");
  clearInterval(readCardInterval);
}

function submitForm() {
  openReadCardModal();
  //form.submit();
}