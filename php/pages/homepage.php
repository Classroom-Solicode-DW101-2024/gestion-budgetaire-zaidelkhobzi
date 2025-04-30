<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Gestion Budgétaire</title>
    <link rel="stylesheet" href="../../public/css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include "../includes/header_homepage.php" ?>

    <section class="hero">
        <div class="container">
            <h1>Simplifiez votre gestion financière</h1>
            <p>Une solution complète pour gérer vos finances personnelles et professionnelles. Suivez vos dépenses, créez des budgets, et atteignez vos objectifs financiers.</p>
            <div class="cta-buttons">
                <a href="#signup" class="btn btn-secondary">Commencer gratuitement</a>
                <a href="#demo" id="demo" class="btn btn-outline">Voir la démonstration</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h3 class="section-title">Pourquoi choisir Gestion Budgétaire ?</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <h4 class="feature-title">Suivi de budget simplifié</h4>
                    <p class="feature-description">Surveillez vos revenus et vos dépenses en toute simplicité grâce à notre interface intuitive.</p>
                </div>
                <div class="feature-card">
                    <h4 class="feature-title">Rapports détaillés</h4>
                    <p class="feature-description">Générez des rapports pertinents pour mieux comprendre vos habitudes de dépense.</p>
                </div>
                <div class="feature-card">
                    <h4 class="feature-title">Sécurisé & confidentiel</h4>
                    <p class="feature-description">Vos données financières sont protégées par des mesures de sécurité de premier ordre.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Fonctionnalités principales</h2>
                <p>Découvrez comment notre application peut vous aider à maîtriser vos finances</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3>Suivi des dépenses</h3>
                    <p>Enregistrez facilement vos dépenses et vos revenus. Catégorisez automatiquement vos transactions pour une meilleure visibilité.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Budgets personnalisés</h3>
                    <p>Créez des budgets adaptés à vos besoins et recevez des alertes pour rester dans les limites que vous avez définies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Rapports détaillés</h3>
                    <p>Analysez vos habitudes financières grâce à des graphiques interactifs et des rapports personnalisables.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h3>Objectifs d'épargne</h3>
                    <p>Définissez vos objectifs financiers et suivez votre progression pour les atteindre plus rapidement.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Alertes et notifications</h3>
                    <p>Recevez des notifications pour les factures à payer, les dépassements de budget et les opportunités d'économies.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Sécurité avancée</h3>
                    <p>Vos données financières sont sécurisées avec un cryptage de niveau bancaire et une authentification à deux facteurs.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Ce que nos utilisateurs disent</h2>
                <p>Découvrez comment notre application a transformé leur façon de gérer leurs finances</p>
            </div>
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Grâce à cette application, j'ai pu économiser plus de 200€ par mois en identifiant mes dépenses superflues. L'interface est vraiment intuitive et les rapports sont très utiles."</p>
                    </div>
                    <div class="testimonial-author">
                        <p>Marie Dupont, Enseignante</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"En tant que freelance, suivre mes dépenses professionnelles était un cauchemar avant de découvrir cette application. Maintenant, tout est organisé et je peux facilement préparer mes déclarations fiscales."</p>
                    </div>
                    <div class="testimonial-author">
                        <p>Thomas Martin, Designer</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Les fonctionnalités de budgétisation m'ont aidé à rembourser mes dettes d'études en seulement 2 ans. Je recommande vivement cette application à tous ceux qui veulent prendre le contrôle de leurs finances."</p>
                    </div>
                    <div class="testimonial-author">
                        <p>Julie Moreau, Ingénieure</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Prêt à prendre le contrôle de vos finances ?</h2>
            <p>Inscrivez-vous gratuitement et commencez à transformer votre gestion financière dès aujourd'hui.</p>
            <div class="cta-buttons">
                <a href="#signup" class="btn btn-primary">Créer un compte gratuit</a>
            </div>
        </div>
    </section>

    <section class="features" id="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Nos formules</h2>
                <p>Choisissez le plan qui correspond à vos besoins</p>
            </div>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>Gratuit</h3>
                    <div class="feature-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <p><strong>0€ / mois</strong></p>
                    <p>• Suivi de base des dépenses</p>
                    <p>• 3 budgets maximum</p>
                    <p>• Rapports mensuels</p>
                    <p>• Application mobile</p>
                    <br>
                    <a href="#signup" id="commencer" class="btn btn-outline">Commencer</a>
                </div>
                <div class="feature-card">
                    <h3>Premium</h3>
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <p><strong>4,99€ / mois</strong></p>
                    <p>• Fonctionnalités gratuites</p>
                    <p>• Budgets illimités</p>
                    <p>• Catégories personnalisées</p>
                    <p>• Prévisions financières</p>
                    <p>• Exportation des données</p>
                    <br>
                    <a href="#signup" class="btn btn-primary">S'abonner</a>
                </div>
                <div class="feature-card">
                    <h3>Famille</h3>
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <p><strong>9,99€ / mois</strong></p>
                    <p>• Fonctionnalités Premium</p>
                    <p>• Jusqu'à 5 utilisateurs</p>
                    <p>• Budgets partagés</p>
                    <p>• Objectifs communs</p>
                    <p>• Contrôle parental</p>
                    <br>
                    <a href="#signup" class="btn btn-secondary">S'abonner</a>
                </div>
            </div>
        </div>
    </section>

    <?php include "../includes/footer.php" ?>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>