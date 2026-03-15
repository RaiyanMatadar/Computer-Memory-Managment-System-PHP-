CREATE TABLE memory_details (
    id INT(11) NOT NULL AUTO_INCREMENT,
    computer_name VARCHAR(100) NOT NULL,
    ram INT(11) NOT NULL,
    rom INT(11) NOT NULL,
    cache_memory INT(11) NOT NULL,
    total_memory DECIMAL(15,2) DEFAULT NULL,
    memory_type VARCHAR(20) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (id)
);