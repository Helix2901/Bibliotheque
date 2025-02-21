const panier = document.querySelector("#panier-btn");
const modal = document.querySelector(".modal");
const mod = document.querySelector(".modal-content");

const closeButtons = document.querySelector(".close");

panier.addEventListener("click", (e) => {
  e.preventDefault();
  modal.style.display = "block";
  mod.style.background =
    'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("../images/panier.jpeg")';
});

closeButtons.addEventListener("click", () => {
  closeButtons.parentElement.parentElement.style.display = "none"; // Cacher la modal
});
