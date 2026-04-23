CREATE TABLE admin_locker_restrictions(
	id INT PRIMARY KEY IDENTITY(1,1),
	biometrics_id NVARCHAR(255),
	role NVARCHAR(50)
)

-- ══════════════════════════════════════
-- 1. LOCKERS
-- ══════════════════════════════════════
CREATE TABLE [dbo].[admin_lockers] (
    id              INT PRIMARY KEY IDENTITY(1,1),
    locker_number   VARCHAR(20)  NOT NULL,         -- e.g. "1-M01"
    phase           VARCHAR(20)  NOT NULL,          -- Phase 1 / Phase 2 / Phase 3 / Phase 4
    gender          VARCHAR(10)  NOT NULL,          -- Male / Female
    classification  VARCHAR(20)  NOT NULL,          -- Production / Non-Production
    employment_type VARCHAR(20)  NOT NULL,          -- Regular / Probationary
    status          VARCHAR(20)  NOT NULL DEFAULT 'Available', -- Available / Occupied
    pos_x           FLOAT        NOT NULL DEFAULT 0, -- canvas X (Layout page)
    pos_y           FLOAT        NOT NULL DEFAULT 0, -- canvas Y (Layout page)
    created_at      DATETIME     NOT NULL DEFAULT GETDATE(),
    updated_at      DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- 2. LOCKER ASSIGNMENTS
-- ══════════════════════════════════════
CREATE TABLE [dbo].[admin_locker_assignments] (
    id                      INT PRIMARY KEY IDENTITY(1,1),
    locker_id               INT          NOT NULL FOREIGN KEY REFERENCES [dbo].[admin_lockers](id),
    biometrics_id           VARCHAR(50)  NOT NULL,  -- references lrn_master_list.BiometricsID
    submitted_by            VARCHAR(50)  NOT NULL,  -- HR user (lrnph_users.username)
    reviewed_by             VARCHAR(50)  NULL,      -- Admin user (lrnph_users.username)
    status                  VARCHAR(20)  NOT NULL DEFAULT 'Pending', -- Pending / Approved / Rejected
    rejection_note          VARCHAR(500) NULL,      -- filled if Rejected
    date_submitted          DATETIME     NOT NULL DEFAULT GETDATE(),
    date_reviewed           DATETIME     NULL,      -- when Admin acted on it
    date_assigned           DATETIME     NULL,      -- when locker became officially assigned
    is_active               BIT          NOT NULL DEFAULT 1 -- 0 = superseded by reassignment
)

-- ══════════════════════════════════════
-- 3. LOCKER REASSIGNMENTS
-- ══════════════════════════════════════
CREATE TABLE [dbo].[admin_locker_reassignments] (
    id                  INT PRIMARY KEY IDENTITY(1,1),
    biometrics_id       VARCHAR(50)  NOT NULL,  -- employee being moved
    from_locker_id      INT          NOT NULL FOREIGN KEY REFERENCES [dbo].[admin_lockers](id),
    to_locker_id        INT          NOT NULL FOREIGN KEY REFERENCES [dbo].[admin_lockers](id),
    reassigned_by       VARCHAR(50)  NOT NULL,  -- who did the reassignment (Admin or HR)
    reason              VARCHAR(500) NULL,
    reassigned_at       DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- 4. ACTIVITY LOGS
-- ══════════════════════════════════════
CREATE TABLE [dbo].[admin_locker_activity_logs] (
    id              INT PRIMARY KEY IDENTITY(1,1),
    performed_by    VARCHAR(50)  NOT NULL,      -- lrnph_users.username
    action          VARCHAR(100) NOT NULL,      -- e.g. "Approved Assignment", "Reassigned Locker"
    target_table    VARCHAR(50)  NULL,          -- e.g. "admin_locker_assignments"
    target_id       INT          NULL,          -- ID of affected record
    details         VARCHAR(500) NULL,          -- human-readable description
    ip_address      VARCHAR(45)  NULL,
    created_at      DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- 5. EMPLOYEE STATUS HISTORY
-- ══════════════════════════════════════
CREATE TABLE [dbo].[admin_locker_status_history] (
    id              INT PRIMARY KEY IDENTITY(1,1),
    biometrics_id   VARCHAR(50)  NOT NULL,      -- references lrn_master_list.BiometricsID
    old_status      VARCHAR(20)  NOT NULL,      -- Active / Inactive
    new_status      VARCHAR(20)  NOT NULL,
    changed_by      VARCHAR(50)  NOT NULL,      -- lrnph_users.username
    reason          VARCHAR(500) NULL,
    changed_at      DATETIME     NOT NULL DEFAULT GETDATE()
)

CREATE TABLE [dbo].[admin_locker_upload_batch] (
    id              INT PRIMARY KEY IDENTITY(1,1),
    biometrics_id   VARCHAR(50)  NOT NULL,       -- from lrn_master_list
    phase           VARCHAR(20)  NOT NULL,        -- Phase 1-4
    classification  VARCHAR(20)  NOT NULL,        -- Production / Non-Production
    employment_type VARCHAR(20)  NOT NULL,        -- Regular / Probationary
    gender          VARCHAR(10)  NOT NULL,        -- Male / Female
    notes           VARCHAR(500) NULL,
    submitted_by    VARCHAR(50)  NOT NULL,        -- HR user (lrnph_users.username)
    status          VARCHAR(20)  NOT NULL DEFAULT 'Unplotted', -- Unplotted / Plotted
    created_at      DATETIME     NOT NULL DEFAULT GETDATE()
)