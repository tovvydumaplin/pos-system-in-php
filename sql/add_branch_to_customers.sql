-- Add branch_id column to customers table
ALTER TABLE customers ADD COLUMN branch_id INT NULL AFTER phone;

-- Add foreign key constraint
ALTER TABLE customers ADD CONSTRAINT fk_customer_branch 
FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL;

-- Optional: Update existing customers to assign them to a default branch
-- UPDATE customers SET branch_id = 1 WHERE branch_id IS NULL;
