-- Update the unique constraint on services table
-- Allow same service name in different branches, but prevent duplicates within same branch

-- First, drop the old unique constraint on name only
ALTER TABLE services DROP INDEX unique_service_name;

-- Add a new composite unique constraint on (name, branch_id)
ALTER TABLE services ADD UNIQUE KEY unique_service_per_branch (name, branch_id);
