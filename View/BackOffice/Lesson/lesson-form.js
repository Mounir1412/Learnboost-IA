document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  if (!form) return;

  function clearErrors() {
    const existing = document.getElementById('clientErrors');
    if (existing) existing.remove();
  }

  function showErrors(errors) {
    clearErrors();
    const div = document.createElement('div');
    div.id = 'clientErrors';
    div.style.color = 'red';
    div.style.marginBottom = '12px';
    div.innerHTML = errors.map(e => '<div>' + e + '</div>').join('');
    form.insertBefore(div, form.firstChild);
  }

  form.addEventListener('submit', function (e) {
    const moduleId = form.querySelector('[name="module_id"]')?.value || '';
    const title = form.querySelector('[name="title"]')?.value?.trim() || '';
    const content = form.querySelector('[name="content"]')?.value?.trim() || '';
    const durationVal = form.querySelector('[name="duration"]')?.value || '';

    const errors = [];
    if (!moduleId) errors.push('Veuillez sélectionner un module.');
    if (title.length === 0) errors.push('Le titre est requis.');
    if (content.length < 5) errors.push('Le contenu doit contenir au moins 5 caractères.');
    if (durationVal !== '' && isNaN(Number(durationVal))) errors.push('La durée doit être un nombre.');

    if (errors.length) {
      e.preventDefault();
      showErrors(errors);
      return false;
    }

    clearErrors();
    return true;
  });
});
