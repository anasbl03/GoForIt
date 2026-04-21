CREATE DATABASE defis_sportifs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE defis_sportifs;

-- =====================
-- USERS
-- =====================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- CHALLENGES (CRUD PRINCIPAL)
-- =====================
CREATE TABLE challenges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    rules TEXT,
    start_date DATE,
    end_date DATE,
    level VARCHAR(50),
    duration VARCHAR(20),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- PARTICIPATIONS
-- =====================
CREATE TABLE participations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    score INT DEFAULT 0,
    proof VARCHAR(255),
    description TEXT,
    status ENUM('pending','validated') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- =====================
-- FAVORITES
-- =====================
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    challenge_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, challenge_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

-- =====================
-- HOMEPAGE (WYSIWYG OBLIGATOIRE)
-- =====================
CREATE TABLE homepage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hero_title VARCHAR(255),
    hero_text TEXT,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================
-- IMAGES
-- =====================
CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- COMMENTS
-- =====================
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    challenge_id INT,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (challenge_id) REFERENCES challenges(id) ON DELETE CASCADE
);

INSERT INTO users (name,email,password,role) VALUES
('Alice','alice@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Bob','bob@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Charlie','charlie@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('David','david@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Emma','emma@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Lucas','lucas@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Sofia','sofia@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Hugo','hugo@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user'),
('Admin','admin@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin'),
('Noah','noah@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','user');

INSERT INTO challenges (title,category,description,rules,start_date,end_date,level,duration,image) VALUES
('Course 5km','Course','Courir 5km','GPS obligatoire','2026-04-01','2026-04-30','intermediaire','medium','course_5km.jpg'),
('Pompes max','Force','Max pompes 1 min','Dos droit','2026-04-01','2026-04-20','debutant','short','pompes.jpg'),
('Velo 50km','Velo','50km vélo','Preuve GPS','2026-04-10','2026-05-01','intermediaire','medium','velo_50km.jpg'),
('Marathon maison','Course','42km semaine','7 jours max','2026-03-01','2026-03-31','avance','long','marathon.jpg'),
('Squats 200','Force','200 squats','Complet','2026-04-10','2026-05-10','intermediaire','medium','squats.jpg'),
('Gainage 5min','Force','Planche','Dos droit','2026-04-01','2026-04-25','debutant','short','gainage.jpg'),
('10000 pas','Endurance','10000 pas/jour','Téléphone','2026-04-01','2026-05-01','debutant','long','10000_pas.jpg'),
('Corde 500 sauts','Cardio','500 sauts','Sans pause','2026-04-08','2026-04-28','intermediaire','short','corde_sauter.jpg'),
('Sprint 100m','Vitesse','100m max speed','Chrono','2026-04-12','2026-04-30','avance','short','sprint_100m.jpg'),
('Yoga 7 jours','Bien-être','Yoga quotidien','20 min/jour','2026-04-10','2026-05-10','debutant','medium','yoga.jpg');

INSERT INTO favorites (user_id,challenge_id) VALUES
(1,2),(1,3),(2,1),(3,4),(4,5),
(5,6),(6,7),(7,8),(8,9),(9,10);

INSERT INTO participations (user_id,challenge_id,score,status) VALUES
(1,1,100,'validated'),
(2,2,80,'pending'),
(3,3,90,'validated'),
(4,4,120,'validated'),
(5,5,60,'pending'),
(6,6,70,'validated'),
(7,7,110,'pending'),
(8,8,95,'validated'),
(9,9,130,'validated'),
(10,10,85,'pending');

INSERT INTO comments (user_id,challenge_id,content) VALUES
(1,1,'Super défi !'),
(2,2,'Très dur mais bien'),
(3,3,'Top expérience'),
(4,4,'Excellent challenge'),
(5,5,'J’ai souffert 😅'),
(6,6,'Très motivant'),
(7,7,'Simple mais efficace'),
(8,8,'Cardio intense'),
(9,9,'Ultra rapide'),
(10,10,'Relaxant et utile');

INSERT INTO homepage (hero_title,hero_text,content)
VALUES (
'Bienvenue sur Défis Sportifs',
'Relevez des défis sportifs et progressez chaque jour',
'Plateforme simple pour suivre vos performances sportives'
);