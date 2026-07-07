document.addEventListener('DOMContentLoaded', function () {
    const sortToggle = document.getElementById('sort-toggle');
    const sortOptions = document.getElementById('sort-options');
    const sortArrow = document.getElementById('sort-arrow');
    const currentSort = document.getElementById('current-sort');
    const sortItems = document.querySelectorAll('.sort-item');

    function toggleDropdown() {
        const isHidden = sortOptions.classList.contains('hidden');

        if (isHidden) {
            sortOptions.classList.remove('hidden');
            setTimeout(() => {
                sortOptions.classList.remove('opacity-0', 'scale-95');
                sortOptions.classList.add('opacity-100', 'scale-100');
            }, 10);
            if (sortArrow) sortArrow.classList.add('rotate-180');
        } else {
            sortOptions.classList.remove('opacity-100', 'scale-100');
            sortOptions.classList.add('opacity-0', 'scale-95');
            if (sortArrow) sortArrow.classList.remove('rotate-180');

            setTimeout(() => {
                sortOptions.classList.add('hidden');
            }, 200);
        }
    }

    if (sortToggle) {
        sortToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown();
        });
    }

    sortItems.forEach(item => {
        item.addEventListener('click', function () {
            const selectedValue = this.getAttribute('data-value');
            const selectedText = this.textContent;

            if (currentSort) {
                currentSort.textContent = `Sort by: ${selectedText}`;
            }

            toggleDropdown();
            executeSortLogic(selectedValue);
        });
    });

    document.addEventListener('click', function (e) {
        if (sortOptions && !sortOptions.classList.contains('hidden')) {
            if (sortToggle && !sortToggle.contains(e.target) && !sortOptions.contains(e.target)) {
                sortOptions.classList.remove('opacity-100', 'scale-100');
                sortOptions.classList.add('opacity-0', 'scale-95');
                if (sortArrow) sortArrow.classList.remove('rotate-180');
                setTimeout(() => {
                    sortOptions.classList.add('hidden');
                }, 200);
            }
        }
    });


    function executeSortLogic(type) {
        const products = Array.from(document.querySelectorAll('.items'));
        if (products.length === 0) return;


        const gridContainer = products[0].parentElement;

        products.sort((a, b) => {
            if (type === 'name-az' || type === 'name-za') {
                const nameA = (a.getAttribute('data-name') || '').toLowerCase().trim();
                const nameB = (b.getAttribute('data-name') || '').toLowerCase().trim();

                if (type === 'name-az') return nameA.localeCompare(nameB);
                if (type === 'name-za') return nameB.localeCompare(nameA);
            }

            if (type === 'price-asc' || type === 'price-desc') {
                const priceA = parseFloat(a.getAttribute('data-price')) || 0;
                const priceB = parseFloat(b.getAttribute('data-price')) || 0;

                if (type === 'price-asc') return priceA - priceB;
                if (type === 'price-desc') return priceB - priceA;
            }
            return 0;
        });

        const fragment = document.createDocumentFragment();
        products.forEach(product => fragment.appendChild(product));

        gridContainer.innerHTML = '';
        gridContainer.appendChild(fragment);
    }
});