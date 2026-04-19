<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Maintenance - API Test</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .test-section {
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .test-section-header {
            background: #f5f5f5;
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        .test-section-header h2 {
            font-size: 1.2em;
            color: #333;
        }
        .test-section-body {
            padding: 20px;
        }
        .test-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }
        .test-item.success {
            border-left: 4px solid #4caf50;
            background: #f1f8f4;
        }
        .test-item.error {
            border-left: 4px solid #f44336;
            background: #fef1f1;
        }
        .test-item.pending {
            border-left: 4px solid #ff9800;
            background: #fff8f0;
        }
        .test-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .test-item-title {
            font-weight: bold;
            color: #333;
        }
        .test-item-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
            color: white;
        }
        .test-item-status.success { background: #4caf50; }
        .test-item-status.error { background: #f44336; }
        .test-item-status.pending { background: #ff9800; }
        .test-item-info {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
        .test-item-url {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 8px;
            border-radius: 3px;
            font-size: 0.85em;
            word-break: break-all;
        }
        .test-item-response {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 300px;
            overflow-y: auto;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .summary-card.total { background: #e3f2fd; }
        .summary-card.success { background: #e8f5e9; }
        .summary-card.error { background: #ffebee; }
        .summary-card.pending { background: #fff3e0; }
        .summary-card h3 {
            font-size: 2em;
            margin-bottom: 5px;
        }
        .summary-card p {
            color: #666;
            font-size: 0.9em;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        pre {
            margin: 0;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Expert Maintenance - API Test</h1>
            <p>Test et validation des endpoints de l'API Backend</p>
        </div>

        <div class="content">
            <!-- Summary -->
            <div class="summary">
                <div class="summary-card total">
                    <h3 id="total-count">0</h3>
                    <p>Total Tests</p>
                </div>
                <div class="summary-card success">
                    <h3 id="success-count" style="color: #4caf50;">0</h3>
                    <p>Succès</p>
                </div>
                <div class="summary-card error">
                    <h3 id="error-count" style="color: #f44336;">0</h3>
                    <p>Échecs</p>
                </div>
                <div class="summary-card pending">
                    <h3 id="pending-count" style="color: #ff9800;">0</h3>
                    <p>En attente</p>
                </div>
            </div>

            <!-- Configuration -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>⚙️ Configuration</h2>
                </div>
                <div class="test-section-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="api-url">URL de l'API:</label>
                            <input type="text" id="api-url" value="http://localhost/ExpertMaintenance/backend/api.php">
                        </div>
                        <div class="form-group">
                            <label for="employee-id">ID Employé:</label>
                            <input type="number" id="employee-id" value="1">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="test-login">Login:</label>
                            <input type="text" id="test-login" value="admin">
                        </div>
                        <div class="form-group">
                            <label for="test-password">Password:</label>
                            <input type="password" id="test-password" value="admin123">
                        </div>
                    </div>
                    <div class="actions">
                        <button class="btn btn-primary" onclick="runAllTests()">🚀 Lancer tous les tests</button>
                        <button class="btn btn-secondary" onclick="resetTests()">🔄 Réinitialiser</button>
                    </div>
                </div>
            </div>

            <!-- Database Connection Test -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>🗄️ Connexion Base de Données</h2>
                </div>
                <div class="test-section-body">
                    <div class="test-item pending" id="test-db">
                        <div class="test-item-header">
                            <span class="test-item-title">Connexion à MySQL</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Vérifie la connexion à la base de données</div>
                        <div class="test-item-url">GET ?action=full_sync&last_sync=0&employee_id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Authentication Test -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>🔐 Authentication</h2>
                </div>
                <div class="test-section-body">
                    <div class="test-item pending" id="test-auth">
                        <div class="test-item-header">
                            <span class="test-item-title">Authentification Employé</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Teste la connexion d'un employé</div>
                        <div class="test-item-url">POST ?action=authenticate</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Synchronization Tests -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>🔄 Synchronisation</h2>
                </div>
                <div class="test-section-body">
                    <div class="test-item pending" id="test-sync-full">
                        <div class="test-item-header">
                            <span class="test-item-title">Synchronisation Complète</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Récupère toutes les données avec valsync > 0</div>
                        <div class="test-item-url">GET ?action=full_sync&last_sync=0&employee_id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-employees">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Employés</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_employees&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-clients">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Clients</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_clients&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-sites">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Sites</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_sites&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-interventions">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Interventions</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_interventions&last_sync=0&employee_id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-tasks">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Tâches</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_tasks&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-priorities">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Priorités</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_priorities&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-sync-images">
                        <div class="test-item-header">
                            <span class="test-item-title">Sync Images</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-url">GET ?action=sync_images&last_sync=0</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Intervention Tests -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>📋 Interventions</h2>
                </div>
                <div class="test-section-body">
                    <div class="test-item pending" id="test-get-intervention">
                        <div class="test-item-header">
                            <span class="test-item-title">Détails Intervention</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Récupère les détails d'une intervention</div>
                        <div class="test-item-url">GET ?action=get_intervention&id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-get-history">
                        <div class="test-item-header">
                            <span class="test-item-title">Historique Site</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Historique des interventions par site</div>
                        <div class="test-item-url">GET ?action=get_intervention_history&site_id=1&limit=10</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Image Tests -->
            <div class="test-section">
                <div class="test-section-header">
                    <h2>🖼️ Images</h2>
                </div>
                <div class="test-section-body">
                    <div class="test-item pending" id="test-get-images">
                        <div class="test-item-header">
                            <span class="test-item-title">Images Intervention</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Récupère les images d'une intervention</div>
                        <div class="test-item-url">GET ?action=get_images&intervention_id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                    <div class="test-item pending" id="test-image-binary">
                        <div class="test-item-header">
                            <span class="test-item-title">Image Binaire</span>
                            <span class="test-item-status pending">En attente</span>
                        </div>
                        <div class="test-item-info">Teste le téléchargement d'image binaire</div>
                        <div class="test-item-url">GET ?action=get_image_binary&id=1</div>
                        <div class="test-item-response" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let testResults = {
            total: 0,
            success: 0,
            error: 0,
            pending: 0
        };

        function updateSummary() {
            document.getElementById('total-count').textContent = testResults.total;
            document.getElementById('success-count').textContent = testResults.success;
            document.getElementById('error-count').textContent = testResults.error;
            document.getElementById('pending-count').textContent = testResults.pending;
        }

        function resetTests() {
            testResults = { total: 0, success: 0, error: 0, pending: 0 };
            updateSummary();

            document.querySelectorAll('.test-item').forEach(item => {
                item.className = 'test-item pending';
                item.querySelector('.test-item-status').className = 'test-item-status pending';
                item.querySelector('.test-item-status').textContent = 'En attente';
                item.querySelector('.test-item-response').style.display = 'none';
            });
        }

        function setTestStatus(testId, status, response = null) {
            const testItem = document.getElementById(testId);
            const statusEl = testItem.querySelector('.test-item-status');
            const responseEl = testItem.querySelector('.test-item-response');

            testItem.className = `test-item ${status}`;
            statusEl.className = `test-item-status ${status}`;
            statusEl.textContent = status === 'success' ? '✓ Succès' : status === 'error' ? '✗ Échec' : 'En attente';

            if (response) {
                responseEl.style.display = 'block';
                responseEl.textContent = typeof response === 'object' ? JSON.stringify(response, null, 2) : response;
            }

            // Update counters
            document.querySelectorAll('.test-item').forEach(item => {
                if (item.className.includes('success')) testResults.success++;
                else if (item.className.includes('error')) testResults.error++;
                else if (item.className.includes('pending')) testResults.pending++;
            });
            testResults.total = document.querySelectorAll('.test-item').length;
            updateSummary();
        }

        async function testEndpoint(testId, url, method = 'GET', body = null) {
            const baseUrl = document.getElementById('api-url').value;
            const fullUrl = baseUrl + (url.includes('?') ? '&' : '?') + url.split('?')[1];

            try {
                const options = {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                };

                if (body && method === 'POST') {
                    options.body = JSON.stringify(body);
                }

                const response = await fetch(baseUrl + (url.includes('?') ? url : url), options);
                const data = await response.json();

                if (data.success || response.status === 200) {
                    setTestStatus(testId, 'success', data);
                    return data;
                } else {
                    setTestStatus(testId, 'error', data);
                    return null;
                }
            } catch (error) {
                setTestStatus(testId, 'error', { error: error.message });
                return null;
            }
        }

        async function runAllTests() {
            resetTests();

            const employeeId = document.getElementById('employee-id').value;
            const login = document.getElementById('test-login').value;
            const password = document.getElementById('test-password').value;

            // Test 1: Database Connection (via full_sync)
            await testEndpoint('test-db', `?action=full_sync&last_sync=0&employee_id=${employeeId}`);

            // Test 2: Authentication
            await testEndpoint('test-auth', '?action=authenticate', 'POST', {
                login: login,
                password: password
            });

            // Test 3: Full Sync
            await testEndpoint('test-sync-full', `?action=full_sync&last_sync=0&employee_id=${employeeId}`);

            // Test 4-10: Individual Sync
            await testEndpoint('test-sync-employees', '?action=sync_employees&last_sync=0');
            await testEndpoint('test-sync-clients', '?action=sync_clients&last_sync=0');
            await testEndpoint('test-sync-sites', '?action=sync_sites&last_sync=0');
            await testEndpoint('test-sync-interventions', `?action=sync_interventions&last_sync=0&employee_id=${employeeId}`);
            await testEndpoint('test-sync-tasks', '?action=sync_tasks&last_sync=0');
            await testEndpoint('test-sync-priorities', '?action=sync_priorities&last_sync=0');
            await testEndpoint('test-sync-images', '?action=sync_images&last_sync=0');

            // Test 11-12: Interventions
            await testEndpoint('test-get-intervention', '?action=get_intervention&id=1');
            await testEndpoint('test-get-history', '?action=get_intervention_history&site_id=1&limit=10');

            // Test 13-14: Images
            await testEndpoint('test-get-images', '?action=get_images&intervention_id=1');
            await testEndpoint('test-image-binary', '?action=get_image_binary&id=1');

            alert('Tests terminés! Vérifiez les résultats ci-dessus.');
        }

        // Initialize
        updateSummary();
    </script>
</body>
</html>
