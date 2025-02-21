-- MaBase

CREATE TABLE Livres (
	IdLivre INT PRIMARY KEY auto_increment,
	Titre VARCHAR(50),
    Auteur VARCHAR(50),
    Editeur VARCHAR(50),
    AnnéeDeParution INT,
    Catégorie VARCHAR(50),
    Stock INT
    ) engine = InnoDB;
    
CREATE TABLE Utilisateur (
	NbUsager INT PRIMARY KEY auto_increment,
    Nom VARCHAR(50),
    Prenom VARCHAR(50),
    DateDeNaissance INT,
    AdresseMail VARCHAR(50)
    ) engine = InnoDB;
    
CREATE TABLE Emprunt (
		LivreEmprunté INT,
        Usager INT,
        DateDebut DATE,
        DateFin DATE,
        PRIMARY KEY (LivreEmprunté, Usager),
        FOREIGN KEY (LivreEmprunté) REFERENCES Livres(IdLivre),
        FOREIGN KEY (Usager) REFERENCES Utilisateur(NbUsager)
        ) engine = InnoDB;
        
CREATE TABLE Administrateurs (
    NbAdmin INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50),
    MotDePasse VARCHAR(50)
) engine = InnoDB;
