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
    const title = form.querySelector('[name="title"]')?.value?.trim() || '';
    const description = form.querySelector('[name="description"]')?.value?.trim() || '';
    const status = form.querySelector('[name="status"]')?.value || '';

    const errors = [];
    if (title.length === 0) errors.push('Le titre est requis.');
    if (description.length < 10) errors.push('La description doit contenir au moins 10 caractères.');
    if (status.length === 0) errors.push('Le statut est requis.');

    if (errors.length) {
      e.preventDefault();
      showErrors(errors);
      return false;
    }

    clearErrors();
    return true;
  });
});
