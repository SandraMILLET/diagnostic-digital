
document.addEventListener('DOMContentLoaded', () => {
  const intro = document.getElementById('intro');
  const startBtn = document.getElementById('start-btn');
  const form = document.getElementById('diagnostic-form');
  const navContainer = document.getElementById('nav-container');
  const prevBtn = document.getElementById('prev-btn');
  const submitBtn = document.getElementById('submit-btn');

  let currentIndex = 0;
  let questionsData = [];
  const answers = new Array(10).fill(null);

  fetch('data_conseils.json')
    .then(response => response.json())
    .then(data => {
      questionsData = data.questions;

      startBtn.addEventListener('click', () => {
        intro.classList.add('hidden');
        form.classList.remove('hidden');
        navContainer.classList.remove('hidden');
        showQuestion(currentIndex);
      });
    });

  function showQuestion(index) {
    form.innerHTML = '';

    const q = questionsData[index];
    const questionBlock = document.createElement('div');
    questionBlock.className = 'mb-4';

    const label = document.createElement('label');
    label.className = 'block font-semibold mb-2 text-[#412e7e]';
    label.innerText = `${index + 1}. ${q.question}`;
    questionBlock.appendChild(label);

    q.answers.forEach((a, i) => {
      const option = document.createElement('div');
      option.className = 'mb-1';

      const input = document.createElement('input');
      input.type = 'radio';
      input.name = `question-${q.id}`;
      input.value = a.score;
      input.id = `q${q.id}-a${i}`;
      input.className = 'mr-2';

      if (answers[index] == a.score) {
        input.checked = true;
      }

      const answerLabel = document.createElement('label');
      answerLabel.setAttribute('for', input.id);
      answerLabel.innerText = a.label;

      input.addEventListener('change', () => {
        answers[index] = parseInt(input.value);
        if (currentIndex < questionsData.length - 1) {
          currentIndex++;
          showQuestion(currentIndex);
        } else {
          submitBtn.style.display = 'inline-block';
        }
      });

      option.appendChild(input);
      option.appendChild(answerLabel);
      questionBlock.appendChild(option);
    });

    form.appendChild(questionBlock);

    prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
    submitBtn.style.display = index === questionsData.length - 1 ? 'inline-block' : 'none';
    // 🔥 Barre de progression dynamique
  const progressPercent = ((index + 1) / questionsData.length) * 100;
  const progressBar = document.getElementById('progressBar');
  const progressText = document.getElementById('progressText');
  if (progressBar) progressBar.style.width = `${progressPercent}%`;
  if (progressText) progressText.textContent = `Question ${index + 1} / ${questionsData.length}`;
}

  prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex--;
      showQuestion(currentIndex);
    }
  });

  submitBtn.addEventListener('click', (e) => {
    e.preventDefault();
    if (answers.includes(null)) {
      alert("Merci de répondre à toutes les questions.");
      return;
    }
    localStorage.setItem("diagnosticResponses", JSON.stringify(answers));
    window.location.href = "merci.html";
  });
});