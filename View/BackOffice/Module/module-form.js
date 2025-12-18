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
    const courseId = form.querySelector('[name="course_id"]')?.value || '';
    const title = form.querySelector('[name="title"]')?.value?.trim() || '';

    const errors = [];
    if (!courseId) errors.push('Veuillez sélectionner un cours.');
    if (title.length === 0) errors.push('Le titre du module est requis.');

    if (errors.length) {
      e.preventDefault();
      showErrors(errors);
      return false;
    }

    clearErrors();
    return true;
  });
});
