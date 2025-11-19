function validateRegisterForm() {
    const nom = document.getElementById("nom");
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    let errors = [];

    if (!nom || nom.value.trim().length < 2) errors.push("Nom trop court.");
    if (!prenom || prenom.value.trim().length < 2) errors.push("Prénom trop court.");
    if (!email || !email.value.trim().includes("@")) errors.push("Email invalide.");
    if (!password || password.value.trim().length < 4) errors.push("Mot de passe trop court.");

    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false;
    }
    return true;
}

function validateEdit() {
    const nom = document.getElementById("nom");
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    if (!nom || nom.value.trim().length < 2) {
        alert("Le nom doit contenir au moins 2 caractères.");
        return false;
    }
    if (!prenom || prenom.value.trim().length < 2) {
        alert("Le prénom doit contenir au moins 2 caractères.");
        return false;
    }
    if (!email || !email.value.trim().includes("@") || !email.value.trim().includes(".")) {
        alert("Email invalide.");
        return false;
    }
    if (password && password.value.trim() !== "" && password.value.trim().length < 4) {
        alert("Le mot de passe doit contenir au moins 4 caractères.");
        return false;
    }

    return true;
}

function validateLoginForm() {
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    if (!email || !email.value.trim().includes("@") || !password || password.value.trim() === "") {
        alert("Email ou mot de passe incorrect.");
        return false;
    }
    return true;
}