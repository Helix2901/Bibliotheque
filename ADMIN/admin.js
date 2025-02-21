// Récupérer tous les éléments avec la classe "modal"
const modals = document.querySelectorAll(".modal");

// Récupérer tous les liens qui ouvriront les modals
const modalLinks = document.querySelectorAll(".navigaytion");
const mod = document.querySelectorAll(".modal-content");

// Ajouter un événement de clic à chaque lien pour ouvrir la modal correspondante
modalLinks.forEach((link, index) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    if (modals[index]) {
      // Vérifier si l'élément existe
      modals[index].style.display = "block";
      if (index == 0) {
        mod[index].style.background =
          'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("images/ajoutlivre.jpeg")';
      }
      if (index == 1) {
        mod[index].style.background =
          'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("images/modiflivre.jpeg")';
      }
      if (index == 2) {
        mod[index].style.background =
          'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("images/supplivre.jpeg")';
      }
      if (index == 3) {
        mod[index].style.background =
          'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("images/ajtusr1.jpeg")';
      }
      if (index == 4) {
        mod[index].style.background =
          'linear-gradient(to bottom,rgba(0, 0, 0, 0.3) 0%, rgba(83, 81, 81, 0.7) 75%, #464444 100%),url("images/deltusr1.jpeg")';
      }
    } else {
      alert("Erreur!!" + index);
    }
  });
});

// Récupérer tous les éléments avec la classe "close" pour fermer les modals
const closeButtons = document.querySelectorAll(".close");

// Ajouter un événement de clic à chaque bouton de fermeture pour fermer la modal correspondante
closeButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const modal = button.parentElement.parentElement; // Récupérer le parent du parent du bouton de fermeture (le div.modal)
    modal.style.display = "none"; // Cacher la modal
  });
});
