document.addEventListener('DOMContentLoaded', function () {
    // Call a function specifically designed to add the first panelist form without needing an event.
    addInitialPanelistForm();
});


document.querySelectorAll('.panelist')
    .forEach((panelist) => {
        addPanelistFormDeleteLink(panelist)
    });

document.querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('div');
    item.classList.add('ml-4');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    styleFormFields(item);

    addPanelistFormDeleteLink(item);
};

function addPanelistFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('block', 'text-sm', 'text-gray-700', 'dark:text-gray-300', 'font-medium');
    removeFormButton.innerText = 'Delete this panelist';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
};

function addInitialPanelistForm() {
    const collectionHolder = document.querySelector('.panelists'); // Use the correct selector for your collection holder.

    const item = document.createElement('div');
    item.classList.add('ml-4');
    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);
    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;
    styleFormFields(item);
}

function styleFormFields(item) {
    const labels = item.querySelectorAll('label');
    const inputs = item.querySelectorAll('input');

    labels.forEach(label => {
        const labelClasses = 'block text-xs text-gray-700 dark:text-gray-300 font-medium'.split(' ');
        label.classList.add(...labelClasses);
    });

    inputs.forEach(input => {
        const inputClasses = 'block w-full shadow-sm border-gray-300 dark:border-transparent dark:text-gray-800 rounded-md border p-2 mt-1 mb-2'.split(' ');
        input.classList.add(...inputClasses);
    });
}





