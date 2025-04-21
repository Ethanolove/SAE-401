<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
ob_start(); // Empêche l'affichage avant les redirections

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Credentials: true");

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Entity\Stores;
use Entity\Products;
use Entity\Categories;
use Entity\Brands;
use Entity\Stocks;
use Entity\Employees;
use LDAP\Result;

require __DIR__ . "/bootstrap.php";
require_once("vendor/autoload.php");

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        if (isset($_REQUEST['action'])) {
            // Get all stores
            if ($_GET['action'] == 'stores') {
                $storeRepo = $entityManager->getRepository(Stores::class);
                $stores = $storeRepo->findAll();
                
                header('Content-Type: application/json');
                echo json_encode($stores);
                exit;
            }
            // Get store by id
            else if ($_REQUEST['action'] == 'store' && isset($_REQUEST['id'])) {
                $store = $entityManager->getRepository(Stores::class)->find($_REQUEST['id']);
                echo json_encode($store);
            }
            // Get all products
            else if ($_REQUEST['action'] == 'products' && !isset($_REQUEST['id'])) {
                $products = $entityManager->getRepository(Products::class)->findAll();
                echo json_encode($products);
            }
            // Get all model_years of in table products
            else if ($_REQUEST['action'] == 'years') {
                $years = $entityManager->getRepository(Products::class)->createQueryBuilder('P')
                    ->select('DISTINCT(P.model_year)')
                    ->getQuery()
                    ->getResult();
                $newyears = array();
                foreach ($years as $key => $value) {
                    $newyears[$key] = $value[1];
                }
                echo json_encode($newyears);
            }
            // Get product by id
            else if ($_REQUEST['action'] == 'product' && isset($_REQUEST['id'])) {
                $product = $entityManager->getRepository(Products::class)->find($_REQUEST['id']);
                echo json_encode($product);
            }
            // Get all categories
            else if ($_REQUEST['action'] == 'categories' && !isset($_REQUEST['id'])) {
                $categories = $entityManager->getRepository(Categories::class)->findAll();
                echo json_encode($categories);
            }
            // Get category by id
            else if ($_REQUEST['action'] == 'category' && isset($_REQUEST['id'])) {
                $category = $entityManager->getRepository(Categories::class)->find($_REQUEST['id']);
                echo json_encode($category);
            }
            // Get all brands
            else if ($_REQUEST['action'] == 'brands' && !isset($_REQUEST['id'])) {
                $brands = $entityManager->getRepository(Brands::class)->findAll();
                echo json_encode($brands);
            }
            // Get brand by id
            else if ($_REQUEST['action'] == 'brand' && isset($_REQUEST['id'])) {
                $brand = $entityManager->getRepository(Brands::class)->find($_REQUEST['id']);
                echo json_encode($brand);
            }
            // Get all stocks
            else if ($_REQUEST['action'] == 'stocks' && !isset($_REQUEST['id'])) {
                $stocks = $entityManager->getRepository(Stocks::class)->findAll();
                echo json_encode($stocks);
            }
            // Get stock by id
            else if ($_REQUEST['action'] == 'stock' && isset($_REQUEST['id'])) {
                $stock = $entityManager->getRepository(Stocks::class)->find($_REQUEST['id']);
                echo json_encode($stock);
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'get_stock' && isset($_GET['product_id']) && isset($_GET['store_id'])) {
                $productId = $_GET['product_id'];
                $storeId = $_GET['store_id'];
            
                // Récupérer le stock du produit pour ce magasin
                $stock = $entityManager->getRepository(Stocks::class)->findOneBy([
                    'product' => $productId,  // Utilise l'entité Product ici
                    'store' => $storeId       // Utilise l'entité Store ici
                ]);
            
                if (!$stock) {
                    echo json_encode(['error' => 'Stock non trouvé']);
                    exit;
                }
            
                // Retourner la quantité de stock
                echo json_encode([
                    'quantity' => $stock->getQuantite()
                ]);
                exit;
            }
            // Get all employees
            else if ($_REQUEST['action'] == 'employees' && !isset($_REQUEST['id'])) {
                $employees = $entityManager->getRepository(Employees::class)->findAll();
                echo json_encode($employees);
            }
            // Get employee by id
            else if ($_REQUEST['action'] == 'employee' && isset($_REQUEST['id'])) {
                $employee = $entityManager->getRepository(Employees::class)->find($_REQUEST['id']);
                echo json_encode($employee);
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'employees_by_store' && isset($_GET['store_id'])) {
                $storeId = $_GET['store_id'];
                
                // Récupérer tous les employés associés au magasin avec l'ID spécifié
                $employees = $entityManager->getRepository(Employees::class)->findBy(['store' => $storeId]);
            
                if (!$employees) {
                    echo json_encode(['error' => 'Aucun employé trouvé pour ce magasin']);
                    exit;
                }
            
                // Créer un tableau avec les informations des employés
                $employeeList = [];
                foreach ($employees as $employee) {
                    $employeeList[] = [
                        'employee_id' => $employee->getEmployeeId(),
                        'name' => $employee->getEmployeeName(),
                        'email' => $employee->getEmployeeEmail(),
                        'role' => $employee->getEmployeeRole(),
                    ];
                }
            
                // Retourner les données des employés au format JSON
                echo json_encode($employeeList);
                exit;
            }
        } else if (
            isset($_GET['brand']) || isset($_GET['category']) ||
            isset($_GET['year']) || isset($_GET['price']) || isset($_GET['limit'])
        ) {
            error_log("Requête de filtrage reçue: " . print_r($_GET, true));
        
            $products = $entityManager->getRepository(Products::class)->createQueryBuilder('p')
                ->join('p.brands', 'b')
                ->join('p.categories', 'c');
        
            // Filtre par marque
            if (!empty($_GET['brand'])) {
                $products->andWhere('b.brand_id = :brand')
                         ->setParameter('brand', $_GET['brand']);
                error_log("Filtre brand appliqué: " . $_GET['brand']);
            }
        
            // Filtre par catégorie
            if (!empty($_GET['category'])) {
                $products->andWhere('c.category_id = :category')
                         ->setParameter('category', $_GET['category']);
                error_log("Filtre category appliqué: " . $_GET['category']);
            }
        
            // Filtre par année
            if (!empty($_GET['year'])) {
                $products->andWhere('p.model_year = :year')
                         ->setParameter('year', $_GET['year']);
                error_log("Filtre year appliqué: " . $_GET['year']);
            }
        
            // Filtre par plage de prix
            if (!empty($_GET['price'])) {
                $priceRange = explode('-', $_GET['price']);
        
                if (count($priceRange) == 2) {
                    $minPrice = (float)trim($priceRange[0]);
                    $maxPrice = (float)trim($priceRange[1]);
        
                    $products->andWhere('p.list_price BETWEEN :minPrice AND :maxPrice')
                             ->setParameter('minPrice', $minPrice)
                             ->setParameter('maxPrice', $maxPrice);
                    error_log("Filtre prix appliqué: $minPrice - $maxPrice");
                }
            }
        
            // Limite de résultats
            if (!empty($_GET['limit'])) {
                $limit = (int)$_GET['limit'];
                $products->setMaxResults($limit);
                error_log("Limite appliquée: $limit");
            }
        
            $products->orderBy('p.list_price', 'ASC');
        
            $query = $products->getQuery();
            error_log("Requête SQL générée: " . $query->getSQL());
            error_log("Paramètres de la requête: " . print_r($query->getParameters(), true));
        
            $result = $query->getResult();
            error_log("Nombre de résultats: " . count($result));
        
            echo json_encode($result);
        }
        break;

    case 'POST':
        if (isset($_REQUEST['action'])) {
            // Add a new store
            if ($_REQUEST['action'] == 'add_store') {
                $store = new Stores();
                $store->setName($_REQUEST['name']);
                $store->setAddress($_REQUEST['address']);
                $store->setCity($_REQUEST['city']);
                $store->setPostalCode($_REQUEST['postal_code']);
                $store->setCountry($_REQUEST['country']);
                $entityManager->persist($store);
                $entityManager->flush();
                echo json_encode($store);
            }
            // Add a new product
            else if ($_REQUEST['action'] == 'add_product') {
                $product = new Products();
                $product->setName($_REQUEST['name']);
                $product->setModelYear($_REQUEST['model_year']);
                $product->setListPrice($_REQUEST['list_price']);
                $product->setBrand($entityManager->getRepository(Brands::class)->find($_REQUEST['brand']));
                $product->setCategory($entityManager->getRepository(Categories::class)->find($_REQUEST['category']));
                $entityManager->persist($product);
                $entityManager->flush();
                echo json_encode($product);
            }
            // Add a new category
            else if ($_REQUEST['action'] == 'addCategory') { 
                $conn = $entityManager->getConnection();
                $maxId = $conn->fetchOne("SELECT MAX(category_id) AS max_id FROM categories");
                $conn->executeStatement("ALTER TABLE categories AUTO_INCREMENT = " . ($maxId + 1));
                $category = new Categories();
                $category->setCategoryName($_REQUEST['category_name']);
                $entityManager->persist($category);
                $entityManager->flush();
                echo json_encode($category);
            }
            // Add a new brand
            else if ($_REQUEST['action'] == 'add_brand') {
                $conn = $entityManager->getConnection();
                $maxId = $conn->fetchOne("SELECT MAX(brand_id) AS max_id FROM brands");
                $conn->executeStatement("ALTER TABLE brands AUTO_INCREMENT = " . ($maxId + 1));
                $brand = new Brands();
                $brand->setBrandName($_REQUEST['brand_name']);
                $entityManager->persist($brand);
                $entityManager->flush();
                echo json_encode($brand);
            }
            // Add a new stock
            else if ($_REQUEST['action'] == 'add_stock') {
                $stock = new Stocks();
                $stock->setProduct($entityManager->getRepository(Products::class)->find($_REQUEST['product']));
                $stock->setStore($entityManager->getRepository(Stores::class)->find($_REQUEST['store']));
                $stock->setQuantity($_REQUEST['quantity']);
                $entityManager->persist($stock);
                $entityManager->flush();
                echo json_encode($stock);
            }
            else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_employee' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['store_id'])) {
    
                $conn = $entityManager->getConnection();
                $maxId = $conn->fetchOne("SELECT MAX(employee_id) AS max_id FROM employees");
                $conn->executeStatement("ALTER TABLE employees AUTO_INCREMENT = " . ($maxId + 1));
                
                // Récupérer l'objet Store via l'ID du store
                $storeId = $_POST['store_id'];
                $store = $entityManager->find('Entity\Stores', $storeId);  // Utilisation de la méthode find pour récupérer l'objet Store
                
                if (!$store) {
                    // Si le store n'est pas trouvé, retourner une erreur
                    echo json_encode(['success' => false, 'message' => 'Magasin non trouvé.']);
                    exit;
                }
                
                $employee = new Employees();
                $employee->setEmployeeName($_POST['name']);
                $employee->setEmployeeEmail($_POST['email']);
                $employee->setEmployeePassword($_POST['password']);
                $employee->setEmployeeRole($_POST['role']);
                $employee->setStoreId($store);  // Passe l'objet Store et non l'ID
                
                $entityManager->persist($employee);
                $entityManager->flush();
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Employé ajouté avec succès.',
                    'employee' => [
                        'employee_id' => $employee->getEmployeeId(),
                        'name' => $employee->getEmployeeName(),
                        'email' => $employee->getEmployeeEmail(),
                        'role' => $employee->getEmployeeRole(),
                        'store_id' => $employee->getStoreId()->getStoreId(),  // Assurez-vous de renvoyer l'ID du store
                    ]
                ]);
            }

            else if (isset($_POST['action'])) {
                if ($_POST['action'] === 'connex') {
                    // Sécurisation et validation des entrées utilisateur
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
                    // Vérification de l'email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["error" => "Email invalide"]);
                        exit;
                    }
            
                    if (!empty($email) && !empty($password)) {
                        if (!isset($entityManager)) {
                            echo json_encode(["error" => "Erreur interne : entityManager non défini"]);
                            exit;
                        }
            
                        // Recherche de l'utilisateur par son email
                        $employee = $entityManager->getRepository(Employees::class)->findOneBy([
                            'employee_email' => $email
                        ]);
            
                        if ($employee) {
                            // Comparaison du mot de passe en clair
                            if ($password === $employee->getEmployeePassword()) {
                                // Mot de passe correct, créer une session
                                session_regenerate_id(true); // Sécurisation de la session
                                $_SESSION['user_id'] = $employee->getEmployeeId();
                                $_SESSION['role'] = $employee->getEmployeeRole();

                                 // Création d'un cookie d'authentification
                                $authToken = bin2hex(random_bytes(16)); // Génération d'un token sécurisé
                                setcookie('auth_token', $authToken, time() + 3600, "/"); // Cookie valable pendant 1 heure
            
                                // Redirection selon le rôle
                                switch ($employee->getEmployeeRole()) {
                                    case 'employee':
                                        echo json_encode(['success' => true, 'redirect_url' => 'Employes/HomeEmp.php']);
                                        exit();
                                    case 'chief':
                                        echo json_encode(['success' => true, 'redirect_url' => 'Chiefs/HomeChief.php']);
                                        exit();
                                    case 'it':
                                        echo json_encode(['success' => true, 'redirect_url' => 'IT/HomeIT.php']);
                                        exit();
                                    default:
                                        echo json_encode(["error" => "Rôle inconnu"]);
                                        exit();
                                }
                            } else {
                                echo json_encode(["error" => "Mot de passe incorrect"]);
                                exit();
                            }
                        } else {
                            echo json_encode(["error" => "Utilisateur introuvable"]);
                            exit();
                        }
                    } else {
                        echo json_encode(["error" => "Veuillez remplir tous les champs"]);
                        exit();
                    }
                }
            }
        }
        break;

        case 'PUT':
            if (isset($_GET['action']) && $_GET['action'] == 'updateEmployeeInfo' && isset($_GET['id'])) {
                $employeeId = $_SESSION['user_id'] ?? null;
                if (!$employeeId || $employeeId != $_GET['id']) {
                    echo json_encode(['error' => 'ID utilisateur invalide ou non connecté']);
                    exit;
                }
        
                // Lire les données envoyées en PUT
                parse_str(file_get_contents("php://input"), $putData);
        
                $employee = $entityManager->getRepository(Employees::class)->find($_GET['id']);
                if (!$employee) {
                    echo json_encode(['error' => 'Utilisateur introuvable']);
                    exit;
                }
        
                if (!empty($putData['employee_name'])) {
                    $employee->setEmployeeName($putData['employee_name']);
                }
        
                if (!empty($putData['employee_email'])) {
                    $employee->setEmployeeEmail($putData['employee_email']);
                }
        
                if (!empty($putData['employee_password'])) {
                    $employee->setEmployeePassword($putData['employee_password']);
                }
        
                $entityManager->flush();
        
                echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
                exit;
            } 
            
            else if (isset($_GET['action']) && $_GET['action'] == 'updateStoreInfo' && isset($_GET['id'])) {
                $employeeId = $_SESSION['user_id'] ?? null;
                if (!$employeeId || $employeeId != $_GET['id']) {
                    echo json_encode(['error' => 'ID utilisateur invalide ou non connecté']);
                    exit;
                }
        
                // Lire les données envoyées en PUT
                parse_str(file_get_contents("php://input"), $putData);
        
                $store = $entityManager->getRepository(Stores::class)->find($_GET['id']);
                if (!$store) {
                    echo json_encode(['error' => 'Magasin introuvable']);
                    exit;
                }
        
                if (!empty($putData['store_id'])) {
                    $store->setStoreId($putData['store_id']);
                }
        
                if (!empty($putData['store_name'])) {
                    $store->setStoreName($putData['store_name']);
                }
        
                if (!empty($putData['street'])) {
                    $store->setStreet($putData['street']);
                }
        
                if (!empty($putData['city'])) {
                    $store->setCity($putData['city']);
                }
        
                if (!empty($putData['state'])) {
                    $store->setState($putData['state']);
                }
        
                if (!empty($putData['zip_code'])) {
                    $store->setZipCode($putData['zip_code']);
                }
        
                if (!empty($putData['phone'])) {
                    $store->setPhone($putData['phone']);
                }
        
                if (!empty($putData['email'])) {
                    $store->setEmail($putData['email']);
                }
        
                $entityManager->flush();
        
                echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
                exit;
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'updateProductInfo' && isset($_GET['id'])) {
                $employeeId = $_SESSION['user_id'] ?? null;
                if (!$employeeId || $employeeId != $_GET['id']) {
                    echo json_encode(['error' => 'ID utilisateur invalide ou non connecté']);
                    exit;
                }
        
                // Lire les données envoyées en PUT
                parse_str(file_get_contents("php://input"), $putData);
        
                $product = $entityManager->getRepository(Products::class)->find($_GET['id']);
                if (!$product) {
                    echo json_encode(['error' => 'Produit introuvable']);
                    exit;
                }
        
                if (!empty($putData['product_name'])) {
                    $product->setProductName($putData['product_name']);
                }
        
                if (!empty($putData['model_year'])) {
                    $product->setModelYear($putData['model_year']);
                }
        
                if (!empty($putData['list_price'])) {
                    $product->setListPrice($putData['list_price']);
                }
        
                if (!empty($putData['brand'])) {
                    $product->setBrand($entityManager->getRepository(Brands::class)->find($putData['brand']));
                }
        
                if (!empty($putData['category'])) {
                    $product->setCategory($entityManager->getRepository(Categories::class)->find($putData['category']));
                }
        
                $entityManager->flush();
        
                echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
                exit;

            }
            else if (isset($_GET['action']) && $_GET['action'] == 'updateBrand' && isset($_GET['id'])) {
                parse_str(file_get_contents("php://input"), $putData);
            
                $brand = $entityManager->getRepository(Brands::class)->find($_GET['id']);
                if (!$brand) {
                    echo json_encode(['error' => 'Marque introuvable']);
                    exit;
                }
            
                if (!empty($putData['brand_name'])) {
                    $brand->setBrandName($putData['brand_name']);
                }
            
                $entityManager->flush();
            
                echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
                exit;
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'updateCategory' && isset($_GET['id'])) {
                parse_str(file_get_contents("php://input"), $putData);

                $category = $entityManager->getRepository(Categories::class)->find($_GET['id']);
                if (!$category) {
                    echo json_encode(['error' => 'Catégorie introuvable']);
                    exit;
                }

                if (!empty($putData['category_name'])) {
                    $category->setCategoryName($putData['category_name']);
                }

                $entityManager->flush();

                echo json_encode(['success' => true, 'message' => 'Informations mises à jour avec succès']);
                exit;
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'update_stock') {
                // Vérifier la session de l'utilisateur
                $employeeId = $_SESSION['user_id'] ?? null;
                if (!$employeeId) {
                    echo json_encode(['error' => 'Utilisateur non connecté']);
                    exit;
                }
            
                // Lire les données envoyées en PUT (au format JSON)
                $putData = json_decode(file_get_contents("php://input"), true);  // Récupérer et décoder les données JSON
            
                // Vérifier si les données nécessaires sont présentes
                if (isset($putData['product_id']) && isset($putData['store_id']) && isset($putData['quantity'])) {
                    // Trouver le stock pour le produit et le magasin spécifiés
                    $stock = $entityManager->getRepository(Stocks::class)->findOneBy([
                        'product' => $putData['product_id'],
                        'store' => $putData['store_id']
                    ]);
            
                    if (!$stock) {
                        echo json_encode(['error' => 'Stock introuvable pour ce produit dans ce magasin']);
                        exit;
                    }
            
                    // Mettre à jour la quantité
                    if (!empty($putData['quantity'])) {
                        $stock->setQuantity($putData['quantity']);
                    }
            
                    // Sauvegarder les modifications dans la base de données
                    $entityManager->flush();
            
                    echo json_encode(['success' => true, 'message' => 'Stock mis à jour avec succès']);
                } else {
                    echo json_encode(['error' => 'Données manquantes ou invalides']);
                }
            
                exit;
            }

            echo json_encode(['error' => 'Action ou ID invalide']);
            exit;

            
        break;
        case 'DELETE':
            parse_str(file_get_contents("php://input"), $params);
    
            // Fusionner les paramètres GET et POST (au cas où les deux sont utilisés)
            $params = array_merge($_GET, $params);
            
            if (isset($params['action']) && $params['action'] === 'delete_brand' && isset($params['id'])) {
                $brandId = $params['id']; // ID de la marque à supprimer

                // Utiliser Doctrine pour trouver la marque par son ID
                $brand = $entityManager->getRepository(Brands::class)->find($brandId);

                if ($brand) {
                    // La marque existe, on la supprime
                    $entityManager->remove($brand);
                    $entityManager->flush();

                    // Répondre avec succès
                    echo json_encode(['success' => true, 'message' => 'Marque supprimée avec succès.']);
                } else {
                    // Si la marque n'existe pas
                    echo json_encode(['error' => 'Marque non trouvée.']);
                }
            }
            else if (isset($_GET['action']) && $_GET['action'] == 'deleteCategory' && isset($_GET['id'])) {
                $categoryId = $_GET['id'];

                // Utiliser Doctrine pour trouver la catégorie par son ID
                $category = $entityManager->getRepository(Categories::class)->find($categoryId);

                if ($category) {
                    // La catégorie existe, on la supprime
                    $entityManager->remove($category);
                    $entityManager->flush();

                    // Répondre avec succès
                    echo json_encode(['success' => true, 'message' => 'Catégorie supprimée avec succès.']);
                } else {
                    // Si la catégorie n'existe pas
                    echo json_encode(['error' => 'Catégorie non trouvée.']);
                }
            }
            else {
                // Paramètres manquants
                echo json_encode(['error' => 'Paramètres manquants ou action incorrecte.']);
            }
            break;
}
ob_end_flush();