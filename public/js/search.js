// // API endpoints (will be overridden by values from Twig)
// let SEARCH_API_URL = '/api/search';
// let SEARCH_HISTORY_URL = '/api/search/history';
// let APP_ENV = 'dev';
//
// // Update with values from Twig if available
// if (typeof window.SEARCH_API_URL !== 'undefined') {
//     SEARCH_API_URL = window.SEARCH_API_URL;
// }
// if (typeof window.SEARCH_HISTORY_URL !== 'undefined') {
//     SEARCH_HISTORY_URL = window.SEARCH_HISTORY_URL;
// }
// if (typeof window.APP_ENV !== 'undefined') {
//     APP_ENV = window.APP_ENV;
// }
//
// document.addEventListener('DOMContentLoaded', function() {
//     const searchIcon = document.getElementById('search-icon');
//     const searchDropdown = document.getElementById('search-dropdown');
//     const searchInput = document.getElementById('search-input');
//     const recentSearchesContainer = document.getElementById('recent-searches');
//     const resultsContainer = document.getElementById('results-container');
//     const noResults = document.getElementById('no-results');
//     const loading = document.getElementById('loading');
//     const pagination = document.getElementById('pagination');
//     const prevPageBtn = document.getElementById('prev-page');
//     const nextPageBtn = document.getElementById('next-page');
//     const pageInfo = document.getElementById('page-info');
//
//     let currentPage = 1;
//     const resultsPerPage = 5;
//     let currentResults = {};
//     let currentQuery = '';
//
//     // Toggle dropdown visibility
//     if (searchIcon) {
//         searchIcon.addEventListener('click', function() {
//             searchDropdown.style.display = searchDropdown.style.display === 'block' ? 'none' : 'block';
//             if (searchDropdown.style.display === 'block') {
//                 searchInput.focus();
//                 loadRecentSearches();
//             }
//         });
//     }
//
//     // Close dropdown when clicking outside
//     document.addEventListener('click', function(event) {
//         if (searchIcon && searchDropdown &&
//             !searchIcon.contains(event.target) && !searchDropdown.contains(event.target)) {
//             searchDropdown.style.display = 'none';
//         }
//     });
//
//     // Search as you type with debounce
//     let searchTimeout;
//     if (searchInput) {
//         searchInput.addEventListener('input', function() {
//             clearTimeout(searchTimeout);
//             const query = searchInput.value.trim();
//             currentQuery = query;
//
//             if (query.length > 2) {
//                 searchTimeout = setTimeout(() => {
//                     performSearch(query);
//                     currentPage = 1; // Reset to first page on new search
//                 }, 500);
//             } else {
//                 clearResults();
//                 if (pagination) pagination.style.display = 'none';
//             }
//         });
//     }
//
//     // Pagination handlers
//     if (prevPageBtn) {
//         prevPageBtn.addEventListener('click', function() {
//             if (currentPage > 1) {
//                 currentPage--;
//                 displayResults(currentResults, currentPage);
//                 updatePagination();
//             }
//         });
//     }
//
//     if (nextPageBtn) {
//         nextPageBtn.addEventListener('click', function() {
//             const totalPages = Math.max(
//                 Math.ceil((currentResults.merchants || []).length / resultsPerPage),
//                 Math.ceil((currentResults.transactions || []).length / resultsPerPage),
//                 Math.ceil((currentResults.users || []).length / resultsPerPage)
//             );
//
//             if (currentPage < totalPages) {
//                 currentPage++;
//                 displayResults(currentResults, currentPage);
//                 updatePagination();
//             }
//         });
//     }
//
//     // Load recent searches from API
//     async function loadRecentSearches() {
//         if (!recentSearchesContainer) return;
//
//         try {
//             const response = await fetch(SEARCH_HISTORY_URL, {
//                 method: 'GET',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-Requested-With': 'XMLHttpRequest'
//                 },
//                 credentials: 'include'
//             });
//
//             if (!response.ok) {
//                 throw new Error('Failed to fetch search history');
//             }
//
//             const data = await response.json();
//             recentSearchesContainer.innerHTML = '';
//
//             if (!data.history || data.history.length === 0) {
//                 const noRecentItem = document.createElement('div');
//                 noRecentItem.className = 'dropdown-item';
//                 noRecentItem.innerHTML = '<span>No recent searches</span>';
//                 recentSearchesContainer.appendChild(noRecentItem);
//                 return;
//             }
//
//             data.history.forEach(search => {
//                 const searchItem = document.createElement('div');
//                 searchItem.className = 'dropdown-item recent-search';
//                 searchItem.innerHTML = `
//                     <div>
//                         <i class="fas fa-search"></i>
//                         <span>${search.query}</span>
//                     </div>
//                     <div class="remove-search" data-id="${search.id}">
//                         <i class="fas fa-times"></i>
//                     </div>
//                 `;
//
//                 // Add click event to recent search item
//                 searchItem.querySelector('div:first-child').addEventListener('click', function() {
//                     if (searchInput) searchInput.value = search.query;
//                     currentQuery = search.query;
//                     performSearch(search.query);
//                 });
//
//                 // Add click event to remove button
//                 searchItem.querySelector('.remove-search').addEventListener('click', function(e) {
//                     e.stopPropagation();
//                     removeRecentSearch(search.id);
//                 });
//
//                 recentSearchesContainer.appendChild(searchItem);
//             });
//         } catch (error) {
//             console.error('Error loading search history:', error);
//             recentSearchesContainer.innerHTML = '<div class="dropdown-item"><span>Error loading history</span></div>';
//         }
//     }
//
//     // Remove recent search
//     async function removeRecentSearch(id) {
//         try {
//             const response = await fetch(`${SEARCH_HISTORY_URL}/${id}`, {
//                 method: 'DELETE',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-Requested-With': 'XMLHttpRequest'
//                 },
//                 credentials: 'include'
//             });
//
//             if (!response.ok) {
//                 throw new Error('Failed to delete search history item');
//             }
//
//             loadRecentSearches();
//         } catch (error) {
//             console.error('Error removing search history:', error);
//         }
//     }
//
//     // Perform search
//     async function performSearch(query) {
//         if (query.length < 3) return;
//
//         if (loading) loading.style.display = 'block';
//         if (noResults) noResults.style.display = 'none';
//         if (resultsContainer) resultsContainer.innerHTML = '';
//         if (pagination) pagination.style.display = 'none';
//
//         try {
//             const response = await fetch(SEARCH_API_URL, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-Requested-With': 'XMLHttpRequest'
//                 },
//                 body: JSON.stringify({ query: query }),
//                 credentials: 'include'
//             });
//
//             if (!response.ok) {
//                 throw new Error('Search request failed');
//             }
//
//             const results = await response.json();
//             currentResults = results.results || {};
//
//             displayResults(currentResults, currentPage);
//             updatePagination();
//         } catch (error) {
//             console.error('Search error:', error);
//             if (resultsContainer) {
//                 resultsContainer.innerHTML = `
//                     <div class="no-results">
//                         <i class="fas fa-exclamation-triangle"></i>
//                         <p>Error performing search. Please try again.</p>
//                     </div>
//                 `;
//             }
//         } finally {
//             if (loading) loading.style.display = 'none';
//         }
//     }
//
//     // Display results with pagination
//     function displayResults(results, page) {
//         if (!resultsContainer) return;
//
//         resultsContainer.innerHTML = '';
//
//         const hasMerchants = results.merchants && results.merchants.length > 0;
//         const hasTransactions = results.transactions && results.transactions.length > 0;
//         const hasUsers = results.users && results.users.length > 0;
//
//         if (!hasMerchants && !hasTransactions && !hasUsers) {
//             if (noResults) {
//                 noResults.style.display = 'block';
//                 noResults.innerHTML = `
//                     <i class="fas fa-search fa-2x"></i>
//                     <p>No results found for "${currentQuery}"</p>
//                 `;
//             }
//             if (pagination) pagination.style.display = 'none';
//             return;
//         }
//
//         if (noResults) noResults.style.display = 'none';
//         if (pagination) pagination.style.display = 'flex';
//
//         // Display merchants with pagination
//         if (hasMerchants) {
//             const merchantsSection = document.createElement('div');
//             merchantsSection.className = 'results-section';
//             merchantsSection.innerHTML = `<div class="results-header">Merchants (${results.merchants.length} found)</div>`;
//
//             const startIndex = (page - 1) * resultsPerPage;
//             const endIndex = Math.min(startIndex + resultsPerPage, results.merchants.length);
//             const paginatedMerchants = results.merchants.slice(startIndex, endIndex);
//
//             paginatedMerchants.forEach(merchant => {
//                 const merchantElement = document.createElement('div');
//                 merchantElement.className = 'result-item merchant-result';
//                 merchantElement.innerHTML = `
//                     <div class="result-title">${merchant.name}</div>
//                     <div class="result-details">ID: ${merchant.id} | Category: ${merchant.category}</div>
//                 `;
//                 merchantsSection.appendChild(merchantElement);
//             });
//
//             resultsContainer.appendChild(merchantsSection);
//         }
//
//         // Display transactions with pagination
//         if (hasTransactions) {
//             const transactionsSection = document.createElement('div');
//             transactionsSection.className = 'results-section';
//             transactionsSection.innerHTML = `<div class="results-header">Transactions (${results.transactions.length} found)</div>`;
//
//             const startIndex = (page - 1) * resultsPerPage;
//             const endIndex = Math.min(startIndex + resultsPerPage, results.transactions.length);
//             const paginatedTransactions = results.transactions.slice(startIndex, endIndex);
//
//             paginatedTransactions.forEach(transaction => {
//                 const transactionElement = document.createElement('div');
//                 transactionElement.className = 'result-item transaction-result';
//                 transactionElement.innerHTML = `
//                     <div class="result-title">${transaction.id}</div>
//                     <div class="result-details">Amount: $${transaction.amount} | Date: ${transaction.date} | Merchant: ${transaction.merchant}</div>
//                 `;
//                 transactionsSection.appendChild(transactionElement);
//             });
//
//             resultsContainer.appendChild(transactionsSection);
//         }
//
//         // Display users with pagination
//         if (hasUsers) {
//             const usersSection = document.createElement('div');
//             usersSection.className = 'results-section';
//             usersSection.innerHTML = `<div class="results-header">Users (${results.users.length} found)</div>`;
//
//             const startIndex = (page - 1) * resultsPerPage;
//             const endIndex = Math.min(startIndex + resultsPerPage, results.users.length);
//             const paginatedUsers = results.users.slice(startIndex, endIndex);
//
//             paginatedUsers.forEach(user => {
//                 const userElement = document.createElement('div');
//                 userElement.className = 'result-item user-result';
//                 userElement.innerHTML = `
//                     <div class="result-title">${user.name}</div>
//                     <div class="result-details">Email: ${user.email} | Role: ${user.role}</div>
//                 `;
//                 usersSection.appendChild(userElement);
//             });
//
//             resultsContainer.appendChild(usersSection);
//         }
//     }
//
//     // Update pagination controls
//     function updatePagination() {
//         if (!pagination || !pageInfo || !prevPageBtn || !nextPageBtn) return;
//
//         const totalMerchantPages = Math.ceil((currentResults.merchants || []).length / resultsPerPage);
//         const totalTransactionPages = Math.ceil((currentResults.transactions || []).length / resultsPerPage);
//         const totalUserPages = Math.ceil((currentResults.users || []).length / resultsPerPage);
//
//         const totalPages = Math.max(totalMerchantPages, totalTransactionPages, totalUserPages);
//
//         pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
//         prevPageBtn.disabled = currentPage === 1;
//         nextPageBtn.disabled = currentPage === totalPages;
//
//         if (totalPages <= 1) {
//             pagination.style.display = 'none';
//         } else {
//             pagination.style.display = 'flex';
//         }
//     }
//
//     // Clear results
//     function clearResults() {
//         if (resultsContainer) resultsContainer.innerHTML = '';
//         currentResults = {};
//         if (noResults) {
//             noResults.style.display = 'block';
//             noResults.innerHTML = `
//                 <i class="fas fa-search fa-2x"></i>
//                 <p>Enter a search term to find merchants, transactions, or users</p>
//             `;
//         }
//     }
// });
