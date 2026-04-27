CREATE TABLE admin_locker_restrictions(
	id INT PRIMARY KEY IDENTITY(1,1),
	biometrics_id NVARCHAR(255),
	role NVARCHAR(50)
)

-- ══════════════════════════════════════
-- LOCKERS
-- ══════════════════════════════════════
CREATE TABLE admin_lockers (
    id               INT          PRIMARY KEY IDENTITY(1,1),
    locker_number    NVARCHAR(20)  NOT NULL,
    phase            NVARCHAR(20)  NOT NULL,
    gender           NVARCHAR(10)  NOT NULL,
    classification   NVARCHAR(20)  NOT NULL,
    employment_type  NVARCHAR(20)  NOT NULL,
    status           NVARCHAR(20)  NOT NULL DEFAULT 'Available',
    pos_x            FLOAT        NOT NULL DEFAULT 0,
    pos_y            FLOAT        NOT NULL DEFAULT 0,
    locker_length    INT          NULL DEFAULT 18,
    locker_width     INT          NULL DEFAULT 18,
    locker_facing    INT          NULL DEFAULT 0, -- 0 = right, 1 = down, 2 = left, 3 = up
    created_at       DATETIME     NOT NULL DEFAULT GETDATE(),
    updated_at       DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- CHILD LOCKERS
-- ══════════════════════════════════════
CREATE TABLE admin_child_lockers (
    id               INT          PRIMARY KEY IDENTITY(1,1),
    parent_id        INT,
    locker_name      NVARCHAR(20)  NOT NULL,
    status           NVARCHAR(20)  NOT NULL DEFAULT 'Available',
    pos_x            FLOAT        NOT NULL DEFAULT 0,
    pos_y            FLOAT        NOT NULL DEFAULT 0,
    created_at       DATETIME     NOT NULL DEFAULT GETDATE(),
    updated_at       DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- LOCKER ASSIGNMENTS
-- ══════════════════════════════════════
CREATE TABLE admin_locker_assignments (
    id               INT          PRIMARY KEY IDENTITY(1,1),
    locker_id        INT          NOT NULL,
    biometrics_id    NVARCHAR(50)  NOT NULL,
    submitted_by     NVARCHAR(50)  NOT NULL,
    reviewed_by      NVARCHAR(50)  NULL,
    status           NVARCHAR(20)  NOT NULL DEFAULT 'Pending',
    rejection_note   NVARCHAR(500) NULL,
    date_submitted   DATETIME     NOT NULL DEFAULT GETDATE(),
    date_reviewed    DATETIME     NULL,
    date_assigned    DATETIME     NULL,
    is_active        BIT          NOT NULL DEFAULT 1
)

-- ══════════════════════════════════════
-- LOCKER REASSIGNMENTS
-- ══════════════════════════════════════
CREATE TABLE admin_locker_reassignments (
    id               INT          PRIMARY KEY IDENTITY(1,1),
    biometrics_id    NVARCHAR(50)  NOT NULL,
    from_locker_id   INT          NOT NULL,
    to_locker_id     INT          NOT NULL,
    reassigned_by    NVARCHAR(50)  NOT NULL,
    reason           NVARCHAR(500) NULL,
    reassigned_at    DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- ACTIVITY LOGS
-- ══════════════════════════════════════
CREATE TABLE admin_locker_activity_logs (
    id             INT          PRIMARY KEY IDENTITY(1,1),
    performed_by   NVARCHAR(50)  NOT NULL,
    action         NVARCHAR(100) NOT NULL,
    target_table   NVARCHAR(50)  NULL,
    target_id      INT          NULL,
    details        NVARCHAR(500) NULL,
    ip_address     NVARCHAR(45)  NULL,
    created_at     DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- EMPLOYEE STATUS HISTORY
-- ══════════════════════════════════════
CREATE TABLE admin_locker_status_history (
    id             INT          PRIMARY KEY IDENTITY(1,1),
    biometrics_id  NVARCHAR(50)  NOT NULL,
    old_status     NVARCHAR(20)  NOT NULL,
    new_status     NVARCHAR(20)  NOT NULL,
    changed_by     NVARCHAR(50)  NOT NULL,
    reason         NVARCHAR(500) NULL,
    changed_at     DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ══════════════════════════════════════
-- UPLOAD BATCH
-- ══════════════════════════════════════
CREATE TABLE admin_locker_upload_batch (
    id               INT          PRIMARY KEY IDENTITY(1,1),
    biometrics_id    NVARCHAR(50)  NOT NULL,
    phase            NVARCHAR(20)  NOT NULL,
    classification   NVARCHAR(20)  NOT NULL,
    employment_type  NVARCHAR(20)  NOT NULL,
    gender           NVARCHAR(10)  NOT NULL,
    notes            NVARCHAR(500) NULL,
    submitted_by     NVARCHAR(50)  NOT NULL,
    status           NVARCHAR(20)  NOT NULL DEFAULT 'Unplotted',
    created_at       DATETIME     NOT NULL DEFAULT GETDATE()
)

-- ===========================================
-- FOR DEVELOPMENT PURPOSES ONLY
-- ===========================================
-- RUN THESE IF DATABASE IS CORRUPTED
-- RUN THESE IF DATABASE IS CORRUPTED
-- RUN THESE IF DATABASE IS CORRUPTED
-- RUN THESE IF DATABASE IS CORRUPTED
-- RUN THESE IF DATABASE IS CORRUPTED
-- RUN THESE IF DATABASE IS CORRUPTED

-- DROP TABLE IF EXISTS admin_locker_activity_logs;
-- DROP TABLE IF EXISTS admin_locker_status_history;
-- DROP TABLE IF EXISTS admin_locker_upload_batch;
-- DROP TABLE IF EXISTS admin_locker_reassignments;
-- DROP TABLE IF EXISTS admin_locker_assignments;
-- DROP TABLE IF EXISTS admin_lockers;